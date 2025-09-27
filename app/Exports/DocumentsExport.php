<?php

namespace App\Exports;

use App\Models\Pilgrim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Pilgrim::with('campaign')->orderBy('firstname')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pèlerin',
            'Campagne',
            'Passeport',
            'Visa',
            'Vaccination',
            'Photo',
            'Certificat Médical',
            'Statut Général',
            'Date de Dernière Mise à Jour'
        ];
    }

    public function map($pilgrim): array
    {
        $documents = json_decode($pilgrim->documents ?? '{}', true);

        $documentTypes = ['passport', 'visa', 'vaccination', 'photo', 'medical'];
        $allComplete = true;

        foreach ($documentTypes as $type) {
            if (!isset($documents[$type]) || empty($documents[$type])) {
                $allComplete = false;
                break;
            }
        }

        return [
            $pilgrim->id,
            $pilgrim->firstname . ' ' . $pilgrim->lastname,
            $pilgrim->campaign->name ?? 'N/A',
            isset($documents['passport']) && !empty($documents['passport']) ? 'Complet' : 'Manquant',
            isset($documents['visa']) && !empty($documents['visa']) ? 'Complet' : 'Manquant',
            isset($documents['vaccination']) && !empty($documents['vaccination']) ? 'Complet' : 'Manquant',
            isset($documents['photo']) && !empty($documents['photo']) ? 'Complet' : 'Manquant',
            isset($documents['medical']) && !empty($documents['medical']) ? 'Complet' : 'Manquant',
            $allComplete ? 'Complet' : 'Incomplet',
            $pilgrim->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}