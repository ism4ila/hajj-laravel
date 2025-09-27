<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Documents</title>
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
            padding: 5px;
            text-align: center;
            font-size: 8px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .pilgrim-name {
            text-align: left;
        }
        .doc-status-ok {
            color: #28a745;
            font-weight: bold;
        }
        .doc-status-missing {
            color: #dc3545;
            font-weight: bold;
        }
        .incomplete-row {
            background-color: #fff3cd;
        }
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
    @php
        $totalPilgrims = $pilgrims->count();
        $documentsComplete = 0;
        $documentStats = [
            'passport' => ['complete' => 0, 'missing' => 0],
            'visa' => ['complete' => 0, 'missing' => 0],
            'vaccination' => ['complete' => 0, 'missing' => 0],
            'photo' => ['complete' => 0, 'missing' => 0],
            'medical' => ['complete' => 0, 'missing' => 0],
        ];

        foreach($pilgrims as $pilgrim) {
            $documents = json_decode($pilgrim->documents ?? '{}', true);
            $isComplete = true;

            foreach(['passport', 'visa', 'vaccination', 'photo', 'medical'] as $docType) {
                if (isset($documents[$docType]) && !empty($documents[$docType])) {
                    $documentStats[$docType]['complete']++;
                } else {
                    $documentStats[$docType]['missing']++;
                    $isComplete = false;
                }
            }

            if ($isComplete) $documentsComplete++;
        }
    @endphp

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
            <div class="report-title">RAPPORT DES DOCUMENTS</div>
            <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="header-right">
            <div style="font-size: 9px;">
                <strong>Total pèlerins:</strong> {{ $totalPilgrims }}<br>
                <strong>Dossiers complets:</strong> {{ $documentsComplete }}<br>
                <strong>Dossiers incomplets:</strong> {{ $totalPilgrims - $documentsComplete }}<br>
                <strong>Taux de complétude:</strong> {{ $totalPilgrims > 0 ? number_format(($documentsComplete / $totalPilgrims) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <div class="summary-box">
        <strong>Statistiques des Documents :</strong><br>
        Passeport : {{ $documentStats['passport']['complete'] }} OK, {{ $documentStats['passport']['missing'] }} manquants |
        Visa : {{ $documentStats['visa']['complete'] }} OK, {{ $documentStats['visa']['missing'] }} manquants |
        Vaccination : {{ $documentStats['vaccination']['complete'] }} OK, {{ $documentStats['vaccination']['missing'] }} manquants |
        Photo : {{ $documentStats['photo']['complete'] }} OK, {{ $documentStats['photo']['missing'] }} manquants |
        Médical : {{ $documentStats['medical']['complete'] }} OK, {{ $documentStats['medical']['missing'] }} manquants
    </div>

    <table>
        <thead>
            <tr>
                <th>Pèlerin</th>
                <th>Campagne</th>
                <th>Passeport</th>
                <th>Visa</th>
                <th>Vaccination</th>
                <th>Photo</th>
                <th>Médical</th>
                <th>Statut Global</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pilgrims as $pilgrim)
                @php
                    $documents = json_decode($pilgrim->documents ?? '{}', true);
                    $documentTypes = ['passport', 'visa', 'vaccination', 'photo', 'medical'];
                    $allComplete = true;
                    foreach ($documentTypes as $type) {
                        if (!isset($documents[$type]) || empty($documents[$type])) {
                            $allComplete = false;
                            break;
                        }
                    }
                @endphp
                <tr class="{{ !$allComplete ? 'incomplete-row' : '' }}">
                    <td class="pilgrim-name">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</td>
                    <td>{{ $pilgrim->campaign->name ?? 'N/A' }}</td>
                    @foreach($documentTypes as $type)
                        <td>
                            @if(isset($documents[$type]) && !empty($documents[$type]))
                                <span class="doc-status-ok">✓</span>
                            @else
                                <span class="doc-status-missing">✗</span>
                            @endif
                        </td>
                    @endforeach
                    <td>
                        @if($allComplete)
                            <span class="doc-status-ok">COMPLET</span>
                        @else
                            <span class="doc-status-missing">INCOMPLET</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin: 20px 0; font-size: 8px;">
        <strong>Légende :</strong>
        <span class="doc-status-ok">✓</span> = Document présent |
        <span class="doc-status-missing">✗</span> = Document manquant |
        Lignes surlignées = Dossiers incomplets
    </div>

    <div class="footer">
        <div>{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }} - Système de Gestion</div>
        <div>{{ $agencySettings['company_phone'] ?? '' }} | {{ $agencySettings['company_email'] ?? '' }}</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>