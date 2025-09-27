<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Pilgrim;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    public function campaignPilgrims(Campaign $campaign, Request $request)
    {
        $format = $request->get('format', 'pdf');
        $pilgrims = $campaign->pilgrims()->with(['payments', 'documents', 'client'])->get();

        $data = [
            'campaign' => $campaign,
            'pilgrims' => $pilgrims,
            'summary' => [
                'total_pilgrims' => $pilgrims->count(),
                'classic_pilgrims' => $pilgrims->where('category', 'classic')->count(),
                'vip_pilgrims' => $pilgrims->where('category', 'vip')->count(),
                'total_revenue' => $pilgrims->sum('paid_amount'),
                'remaining_amount' => $pilgrims->sum('remaining_amount'),
            ]
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data, "pilgrims_campaign_{$campaign->id}");
        }

        return $this->exportToPdf($data, 'exports.campaign-pilgrims', "Liste_Pèlerins_Campagne_{$campaign->id}");
    }

    public function pilgrimPayments(Pilgrim $pilgrim, Request $request)
    {
        $format = $request->get('format', 'pdf');
        $payments = $pilgrim->payments()->orderBy('created_at', 'desc')->get();

        $data = [
            'pilgrim' => $pilgrim->load('client'),
            'payments' => $payments,
            'campaign' => $pilgrim->campaign,
            'client' => $pilgrim->client,
            'summary' => [
                'total_paid' => $payments->where('status', 'completed')->sum('amount'),
                'total_pending' => $payments->where('status', 'pending')->sum('amount'),
                'remaining' => $pilgrim->remaining_amount,
            ]
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data, "payments_pilgrim_{$pilgrim->id}");
        }

        return $this->exportToPdf($data, 'exports.pilgrim-payments', "Paiements_Pèlerin_{$pilgrim->firstname}_{$pilgrim->lastname}");
    }

    public function campaignSummary(Campaign $campaign, Request $request)
    {
        $format = $request->get('format', 'pdf');
        $pilgrims = $campaign->pilgrims()->with('payments')->get();
        $payments = Payment::whereIn('pilgrim_id', $pilgrims->pluck('id'))->get();

        $data = [
            'campaign' => $campaign,
            'statistics' => [
                'total_pilgrims' => $pilgrims->count(),
                'classic_pilgrims' => $pilgrims->where('category', 'classic')->count(),
                'vip_pilgrims' => $pilgrims->where('category', 'vip')->count(),
                'expected_revenue' => $pilgrims->sum('total_amount'),
                'actual_revenue' => $payments->where('status', 'completed')->sum('amount'),
                'pending_payments' => $payments->where('status', 'pending')->sum('amount'),
                'remaining_to_collect' => $pilgrims->sum('remaining_amount'),
                'completion_rate' => $pilgrims->count() > 0 ?
                    round(($payments->where('status', 'completed')->sum('amount') / $pilgrims->sum('total_amount')) * 100, 2) : 0,
            ],
            'category_breakdown' => [
                'classic' => [
                    'count' => $pilgrims->where('category', 'classic')->count(),
                    'expected' => $pilgrims->where('category', 'classic')->sum('total_amount'),
                    'collected' => $pilgrims->where('category', 'classic')->sum('paid_amount'),
                ],
                'vip' => [
                    'count' => $pilgrims->where('category', 'vip')->count(),
                    'expected' => $pilgrims->where('category', 'vip')->sum('total_amount'),
                    'collected' => $pilgrims->where('category', 'vip')->sum('paid_amount'),
                ]
            ]
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data, "summary_campaign_{$campaign->id}");
        }

        return $this->exportToPdf($data, 'exports.campaign-summary', "Résumé_Campagne_{$campaign->name}");
    }

    public function allCampaigns(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $campaigns = Campaign::with(['pilgrims.payments'])->get();

        $data = [
            'campaigns' => $campaigns,
            'global_stats' => [
                'total_campaigns' => $campaigns->count(),
                'total_pilgrims' => $campaigns->sum(fn($c) => $c->pilgrims->count()),
                'total_revenue' => $campaigns->sum(fn($c) => $c->pilgrims->sum('paid_amount')),
                'pending_revenue' => $campaigns->sum(fn($c) => $c->pilgrims->sum('remaining_amount')),
            ]
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data, 'all_campaigns_report');
        }

        return $this->exportToPdf($data, 'exports.all-campaigns', 'Rapport_Toutes_Campagnes');
    }

    public function allClients(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = Client::with(['pilgrims.payments']);

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $clients = $query->orderBy('lastname')->orderBy('firstname')->get();

        $data = [
            'clients' => $clients,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
            'stats' => [
                'total_clients' => $clients->count(),
                'active_clients' => $clients->where('is_active', true)->count(),
                'total_pilgrimages' => $clients->sum(fn($c) => $c->pilgrims->count()),
                'total_spent' => $clients->sum(fn($c) => $c->pilgrims->sum('paid_amount')),
                'nationality_breakdown' => $clients->groupBy('nationality')->map(fn($group) => $group->count()),
                'region_breakdown' => $clients->where('nationality', 'Cameroun')->groupBy('region')->map(fn($group) => $group->count()),
            ]
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($data, 'clients_export');
        }

        return $this->exportToPdf($data, 'exports.clients', 'Rapport_Tous_Clients');
    }

    private function exportToPdf($data, $view, $filename)
    {
        $html = view($view, $data)->render();

        // Simple HTML to PDF using browser print
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '.html"');
    }

    private function exportToExcel($data, $filename)
    {
        // Simple CSV export for Excel compatibility
        $csv = $this->generateCSV($data);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
    }

    private function generateCSV($data)
    {
        $output = '';

        if (isset($data['clients'])) {
            // Clients export
            $output .= "Prénom,Nom,Genre,Date de naissance,Âge,Téléphone,Email,Adresse,Nationalité,Région,Département,Contact d'urgence,Téléphone d'urgence,Passeport,Expiration passeport,Nb pèlerinages,Montant total dépensé,Statut,Date création\n";
            foreach ($data['clients'] as $client) {
                $output .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $client->firstname,
                    $client->lastname,
                    $client->gender === 'male' ? 'Homme' : 'Femme',
                    $client->date_of_birth->format('d/m/Y'),
                    $client->age,
                    $client->phone ?? '',
                    $client->email ?? '',
                    $client->address ?? '',
                    $client->nationality ?? '',
                    $client->region ?? '',
                    $client->department ?? '',
                    $client->emergency_contact ?? '',
                    $client->emergency_phone ?? '',
                    $client->passport_number ?? '',
                    $client->passport_expiry_date ? $client->passport_expiry_date->format('d/m/Y') : '',
                    $client->pilgrims->count(),
                    number_format($client->pilgrims->sum('paid_amount'), 2),
                    $client->is_active ? 'Actif' : 'Inactif',
                    $client->created_at->format('d/m/Y H:i')
                );
            }
        } elseif (isset($data['pilgrims'])) {
            // Pilgrims export
            $output .= "Prénom,Nom,Catégorie,Téléphone,Email,Montant Total,Montant Payé,Restant,Statut\n";
            foreach ($data['pilgrims'] as $pilgrim) {
                $output .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $pilgrim->firstname,
                    $pilgrim->lastname,
                    ucfirst($pilgrim->category),
                    $pilgrim->phone,
                    $pilgrim->email,
                    number_format($pilgrim->total_amount, 2),
                    number_format($pilgrim->paid_amount, 2),
                    number_format($pilgrim->remaining_amount, 2),
                    ucfirst($pilgrim->status)
                );
            }
        } elseif (isset($data['payments'])) {
            // Payments export
            $output .= "Date,Montant,Statut,Notes\n";
            foreach ($data['payments'] as $payment) {
                $output .= sprintf(
                    '"%s","%s","%s","%s"' . "\n",
                    $payment->created_at->format('d/m/Y H:i'),
                    number_format($payment->amount, 2),
                    ucfirst($payment->status),
                    $payment->notes ?? ''
                );
            }
        }

        return $output;
    }
}
