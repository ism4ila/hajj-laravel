<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function query()
    {
        $query = Payment::with(['pilgrim.campaign']);

        if (isset($this->params['from_date'])) {
            $query->whereDate('payment_date', '>=', $this->params['from_date']);
        }

        if (isset($this->params['to_date'])) {
            $query->whereDate('payment_date', '<=', $this->params['to_date']);
        }

        return $query->orderBy('payment_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pèlerin',
            'Campagne',
            'Montant (FCFA)',
            'Mode de Paiement',
            'Date de Paiement',
            'Référence',
            'Statut',
            'Notes',
            'Date de Création'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->pilgrim->firstname . ' ' . $payment->pilgrim->lastname,
            $payment->pilgrim->campaign->name ?? 'N/A',
            number_format($payment->amount, 0, ',', ' '),
            match($payment->payment_method) {
                'cash' => 'Espèces',
                'check' => 'Chèque',
                'bank_transfer' => 'Virement',
                'card' => 'Carte',
                default => $payment->payment_method
            },
            \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y'),
            $payment->reference ?? '',
            match($payment->status) {
                'completed' => 'Terminé',
                'pending' => 'En attente',
                'cancelled' => 'Annulé',
                default => $payment->status
            },
            $payment->notes ?? '',
            $payment->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}