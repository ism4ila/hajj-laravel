<?php

namespace App\Exports;

use App\Models\Pilgrim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PilgrimsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Pilgrim::with('campaign')->orderBy('firstname')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Email',
            'Téléphone',
            'Date de Naissance',
            'Passeport',
            'Adresse',
            'Campagne',
            'Catégorie',
            'Statut',
            'Montant Total (FCFA)',
            'Montant Payé (FCFA)',
            'Montant Restant (FCFA)',
            'Date d\'Inscription'
        ];
    }

    public function map($pilgrim): array
    {
        return [
            $pilgrim->id,
            $pilgrim->firstname,
            $pilgrim->lastname,
            $pilgrim->email ?? '',
            $pilgrim->phone ?? '',
            $pilgrim->birth_date ? \Carbon\Carbon::parse($pilgrim->birth_date)->format('d/m/Y') : '',
            $pilgrim->passport_number ?? '',
            $pilgrim->address ?? '',
            $pilgrim->campaign->name ?? 'N/A',
            ucfirst($pilgrim->category ?? 'Standard'),
            ucfirst($pilgrim->status ?? 'Active'),
            number_format($pilgrim->total_amount, 0, ',', ' '),
            number_format($pilgrim->paid_amount, 0, ',', ' '),
            number_format($pilgrim->remaining_amount, 0, ',', ' '),
            $pilgrim->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}