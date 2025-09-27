<?php

namespace App\Exports;

use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampaignsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Campaign::with('pilgrims')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Type',
            'Statut',
            'Prix Classique (FCFA)',
            'Prix VIP (FCFA)',
            'Date de Départ',
            'Date de Retour',
            'Nombre de Pèlerins',
            'Revenus Totaux (FCFA)',
            'Date de Création'
        ];
    }

    public function map($campaign): array
    {
        return [
            $campaign->id,
            $campaign->name,
            ucfirst($campaign->type),
            ucfirst($campaign->status),
            number_format($campaign->price_classic, 0, ',', ' '),
            number_format($campaign->price_vip, 0, ',', ' '),
            $campaign->departure_date ? \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') : '',
            $campaign->return_date ? \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') : '',
            $campaign->pilgrims->count(),
            number_format($campaign->pilgrims->sum('total_amount'), 0, ',', ' '),
            $campaign->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}