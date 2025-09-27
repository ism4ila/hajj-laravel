<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Pèlerins</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px;
            color: #333;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            opacity: 0.08;
            z-index: -1;
            max-width: 400px;
            max-height: 400px;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .header-center {
            display: table-cell;
            width: 35%;
            text-align: center;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 25%;
            text-align: right;
            vertical-align: top;
        }
        .logo {
            max-height: 60px;
            max-width: 180px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            color: #333;
        }
        .company-info {
            font-size: 9px;
            color: #666;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 8px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .amount-cell {
            text-align: right;
            font-weight: bold;
        }
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }
        .status-pending { color: #ffc107; }
    </style>
</head>
<body>
    {{-- Logo Watermark --}}
    @if($agencySettings['company_logo'] ?? null)
        @php
            $logoPath = storage_path('app/public/logos/' . $agencySettings['company_logo']);
            if (file_exists($logoPath)) {
                $logoContent = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoContent);
                $logoExtension = pathinfo($logoPath, PATHINFO_EXTENSION);
                $watermarkSrc = 'data:image/' . $logoExtension . ';base64,' . $logoBase64;
            }
        @endphp
        @if(isset($watermarkSrc))
            <img src="{{ $watermarkSrc }}" alt="Logo Watermark" class="watermark">
        @endif
    @endif

    <div class="header">
        <div class="header-left">
            @if($agencySettings['company_logo'] ?? null)
                @php
                    $logoPath = storage_path('app/public/logos/' . $agencySettings['company_logo']);
                    if (file_exists($logoPath)) {
                        $logoContent = file_get_contents($logoPath);
                        $logoBase64 = base64_encode($logoContent);
                        $logoExtension = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $logoSrc = 'data:image/' . $logoExtension . ';base64,' . $logoBase64;
                    }
                @endphp
                @if(isset($logoSrc))
                    <img src="{{ $logoSrc }}" alt="Logo" class="logo">
                @endif
            @endif
            <div class="company-name">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</div>
            <div class="company-info">
                @if($agencySettings['company_address'] ?? null){{ $agencySettings['company_address'] }}<br>@endif
                @if($agencySettings['company_phone'] ?? null)Tél: {{ $agencySettings['company_phone'] }}<br>@endif
                @if($agencySettings['company_email'] ?? null)Email: {{ $agencySettings['company_email'] }}@endif
            </div>
        </div>
        <div class="header-center">
            <div class="report-title">RAPPORT DES PÈLERINS</div>
            <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="header-right">
            <div style="font-size: 9px;">
                <strong>Total pèlerins:</strong> {{ $pilgrims->count() }}<br>
                <strong>Total campagnes:</strong> {{ $pilgrims->pluck('campaign')->unique()->count() }}<br>
                <strong>En cours:</strong> {{ $pilgrims->where('status', 'registered')->count() }}
            </div>
        </div>
    </div>

    <div class="summary-box">
        <strong>Résumé des Pèlerins :</strong>
        {{ $pilgrims->where('status', 'active')->count() }} actifs |
        {{ $pilgrims->where('status', 'inactive')->count() }} inactifs |
        {{ $pilgrims->where('status', 'pending')->count() }} en attente |
        Pèlerins avec documents complets : {{ $pilgrims->where('status', 'documents_complete')->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Client / Pèlerin</th>
                <th>Contact</th>
                <th>Campagne</th>
                <th>Catégorie</th>
                <th>Documents</th>
                <th>Paiements</th>
                <th>Dernière MAJ</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pilgrims as $pilgrim)
            <tr>
                <td>
                    @if($pilgrim->client)
                        <strong>{{ $pilgrim->client->full_name }}</strong><br>
                        <small style="color: #666;">Pèlerin: {{ $pilgrim->full_name }}</small>
                    @else
                        {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}<br>
                        <small style="color: #dc3545;">⚠️ Client non associé</small>
                    @endif
                </td>
                <td>
                    @if($pilgrim->client)
                        {{ $pilgrim->client->email ?? $pilgrim->email ?? '-' }}<br>
                        {{ $pilgrim->client->phone ?? $pilgrim->phone ?? '-' }}
                    @else
                        {{ $pilgrim->email ?? '-' }}<br>
                        {{ $pilgrim->phone ?? '-' }}
                    @endif
                </td>
                <td>{{ $pilgrim->campaign->name ?? 'Aucune' }}</td>
                <td class="text-center">{{ ucfirst($pilgrim->category ?? 'Standard') }}</td>
                <td class="text-center">{{ $pilgrim->status === 'documents_complete' ? '✓' : '✗' }}</td>
                <td class="text-center">{{ $pilgrim->payments->count() ?? 0 }}</td>
                <td class="text-center">{{ $pilgrim->updated_at->format('d/m/Y') }}</td>
                <td class="text-center status-{{ $pilgrim->status ?? 'active' }}">
                    {{ strtoupper($pilgrim->status ?? 'ACTIF') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAUX:</td>
                <td class="text-center">{{ $pilgrims->where('status', 'documents_complete')->count() }}</td>
                <td class="text-center">{{ $pilgrims->sum(function($p) { return $p->payments->count(); }) }}</td>
                <td colspan="2" class="text-center">{{ $pilgrims->count() }} pèlerins</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }} - Système de Gestion</div>
        <div>{{ $agencySettings['company_phone'] ?? '' }} | {{ $agencySettings['company_email'] ?? '' }}</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>