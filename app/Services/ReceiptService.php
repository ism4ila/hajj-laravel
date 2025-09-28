<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\ReceiptTemplate;
use App\Models\PaymentHistory;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    /**
     * Générer un reçu pour un paiement
     */
    public function generateReceipt($paymentId, $templateCode = null, $options = [])
    {
        $payment = Payment::with(['pilgrim.client', 'pilgrim.campaign', 'createdBy'])->findOrFail($paymentId);

        // Obtenir le template
        $template = $templateCode
            ? ReceiptTemplate::where('code', $templateCode)->firstOrFail()
            : ReceiptTemplate::getDefault();

        // Créer le reçu
        $receipt = $template->generateReceipt($payment);

        // Enregistrer l'action dans l'historique
        PaymentHistory::logAction(
            $payment,
            'receipt_generated',
            null,
            ['template' => $template->code, 'receipt_id' => $receipt->id],
            "Reçu généré avec le template: {$template->name}"
        );

        return $receipt;
    }

    /**
     * Afficher un reçu
     */
    public function renderReceipt($receiptId, $format = 'html')
    {
        $receipt = Receipt::with(['payment.pilgrim.client', 'payment.pilgrim.campaign', 'generatedBy'])
            ->findOrFail($receiptId);

        $template = ReceiptTemplate::where('code', $receipt->template_version)->first();

        if (!$template) {
            throw new \Exception("Template '{$receipt->template_version}' introuvable");
        }

        $data = $this->prepareReceiptData($receipt);

        switch ($format) {
            case 'pdf':
                return $this->generatePDF($template->template_path, $data);
            case 'html':
            default:
                return View::make($template->template_path, $data)->render();
        }
    }

    /**
     * Préparer les données pour l'affichage du reçu
     */
    private function prepareReceiptData($receipt)
    {
        $payment = $receipt->payment;
        $pilgrim = $payment->pilgrim;

        // Obtenir tous les paiements du pèlerin pour l'historique
        $allPayments = $pilgrim->payments()
            ->where('status', 'completed')
            ->orderBy('payment_date', 'desc')
            ->get();

        // Paramètres de l'agence
        $agencySettings = $receipt->agency_data ?? $this->getAgencySettings();

        return [
            'payment' => $payment,
            'pilgrim' => $pilgrim,
            'receipt' => $receipt,
            'allPayments' => $allPayments,
            'agencySettings' => $agencySettings,
            'servingUser' => $receipt->generatedBy ?? $payment->createdBy,
            'generatedAt' => $receipt->generated_at,
        ];
    }

    /**
     * Générer un PDF
     */
    private function generatePDF($templatePath, $data)
    {
        $html = View::make($templatePath, $data)->render();

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
            ]);
    }

    /**
     * Marquer un reçu comme imprimé
     */
    public function markAsPrinted($receiptId)
    {
        $receipt = Receipt::findOrFail($receiptId);
        $receipt->markAsPrinted();

        // Enregistrer l'action dans l'historique
        PaymentHistory::logAction(
            $receipt->payment,
            'receipt_printed',
            null,
            ['receipt_id' => $receipt->id, 'print_count' => $receipt->print_count],
            "Reçu imprimé (#{$receipt->print_count})"
        );

        return $receipt;
    }

    /**
     * Obtenir les statistiques des reçus
     */
    public function getReceiptStats($period = 'month')
    {
        $query = Receipt::query();

        switch ($period) {
            case 'today':
                $query->whereDate('generated_at', today());
                break;
            case 'week':
                $query->whereBetween('generated_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('generated_at', now()->month);
                break;
            case 'year':
                $query->whereYear('generated_at', now()->year);
                break;
        }

        return [
            'total_generated' => $query->count(),
            'total_printed' => $query->where('is_printed', true)->count(),
            'by_template' => $query->groupBy('template_version')
                ->selectRaw('template_version, count(*) as count')
                ->pluck('count', 'template_version')
                ->toArray(),
            'recent_receipts' => Receipt::with(['payment.pilgrim'])
                ->latest('generated_at')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Obtenir les templates disponibles
     */
    public function getAvailableTemplates()
    {
        return ReceiptTemplate::active()
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }

    /**
     * Créer un aperçu d'un template
     */
    public function previewTemplate($templateCode, $paymentId = null)
    {
        $template = ReceiptTemplate::where('code', $templateCode)->firstOrFail();

        // Utiliser un paiement spécifique ou créer des données de test
        if ($paymentId) {
            $payment = Payment::with(['pilgrim.client', 'pilgrim.campaign'])->findOrFail($paymentId);
        } else {
            $payment = $this->createSamplePaymentData();
        }

        $data = [
            'payment' => $payment,
            'pilgrim' => $payment->pilgrim,
            'allPayments' => collect([$payment]),
            'agencySettings' => $this->getAgencySettings(),
            'servingUser' => auth()->user(),
            'isPreview' => true,
        ];

        return View::make($template->template_path, $data)->render();
    }

    /**
     * Récupérer les paramètres de l'agence
     */
    private function getAgencySettings()
    {
        return [
            'company_name' => SystemSetting::get('company_name', 'SAFIR'),
            'company_logo' => SystemSetting::get('company_logo'),
            'company_address' => SystemSetting::get('company_address'),
            'company_city' => SystemSetting::get('company_city'),
            'company_phone' => SystemSetting::get('company_phone'),
            'company_email' => SystemSetting::get('company_email'),
            'company_registration' => SystemSetting::get('company_registration'),
            'currency_symbol' => SystemSetting::get('currency_symbol', 'FCFA'),
            'default_currency' => SystemSetting::get('default_currency', 'FCFA'),
            'legal_notice' => SystemSetting::get('legal_notice'),
        ];
    }

    /**
     * Créer des données de test pour l'aperçu
     */
    private function createSamplePaymentData()
    {
        return (object) [
            'id' => 12345,
            'amount' => 500000,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'reference' => 'REF-2024-001',
            'status' => 'completed',
            'pilgrim' => (object) [
                'id' => 1,
                'full_name' => 'Ahmed DIALLO',
                'firstname' => 'Ahmed',
                'lastname' => 'DIALLO',
                'email' => 'ahmed.diallo@example.com',
                'phone' => '+221 77 123 45 67',
                'category' => 'classic',
                'total_amount' => 2500000,
                'paid_amount' => 1500000,
                'remaining_amount' => 1000000,
                'client' => (object) [
                    'full_name' => 'Ahmed DIALLO',
                ],
                'campaign' => (object) [
                    'name' => 'Hajj 2024',
                    'type' => 'hajj',
                    'departure_date' => now()->addMonths(3),
                    'return_date' => now()->addMonths(3)->addDays(45),
                ],
            ],
        ];
    }

    /**
     * Dupliquer un reçu existant
     */
    public function duplicateReceipt($receiptId, $newTemplateCode = null)
    {
        $originalReceipt = Receipt::findOrFail($receiptId);

        $templateCode = $newTemplateCode ?? $originalReceipt->template_version;

        return $this->generateReceipt(
            $originalReceipt->payment_id,
            $templateCode,
            ['notes' => 'Duplicata du reçu #' . $originalReceipt->receipt_number]
        );
    }
}