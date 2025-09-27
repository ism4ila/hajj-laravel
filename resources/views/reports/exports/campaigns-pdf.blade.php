<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Campagnes</title>
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
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
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
                <img src="{{ $logoSrc }}" alt="Logo" style="max-height: 60px; max-width: 180px; margin-bottom: 10px;">
            @endif
        @endif
        <div class="company-name">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</div>
        @if($agencySettings['company_address'] ?? null)
            <div>{{ $agencySettings['company_address'] }}</div>
        @endif
        <div class="report-title">RAPPORT DES CAMPAGNES</div>
        <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Résumé :</strong> {{ $campaigns->count() }} campagnes | {{ $campaigns->sum('pilgrims_count') }} pèlerins total
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Prix Classique</th>
                <th>Prix VIP</th>
                <th>Départ</th>
                <th>Retour</th>
                <th>Pèlerins</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->id }}</td>
                <td>{{ $campaign->name }}</td>
                <td>{{ ucfirst($campaign->type) }}</td>
                <td>{{ ucfirst($campaign->status) }}</td>
                <td class="text-right">{{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">{{ number_format($campaign->price_vip, 0, ',', ' ') }} FCFA</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $campaign->pilgrims_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }} - Système de Gestion</div>
        <div>{{ $agencySettings['company_phone'] ?? '' }} | {{ $agencySettings['company_email'] ?? '' }}</div>
    </div>
</body>
</html>