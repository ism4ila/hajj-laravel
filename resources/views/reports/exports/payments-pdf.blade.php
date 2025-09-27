<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Paiements</title>
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
        .status-completed { color: #28a745; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
        .status-cancelled { color: #dc3545; font-weight: bold; }
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
            <div class="report-title">RAPPORT DES PAIEMENTS</div>
            <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
            @if(isset($params['from_date']) || isset($params['to_date']))
                <div style="margin-top: 10px; font-size: 9px;">
                    Période:
                    @if($params['from_date'] ?? null)du {{ \Carbon\Carbon::parse($params['from_date'])->format('d/m/Y') }}@endif
                    @if($params['to_date'] ?? null) au {{ \Carbon\Carbon::parse($params['to_date'])->format('d/m/Y') }}@endif
                </div>
            @endif
        </div>
        <div class="header-right">
            <div style="font-size: 9px;">
                <strong>Total paiements:</strong> {{ $payments->count() }}<br>
                <strong>Montant total:</strong> {{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>

    <div class="summary-box">
        <strong>Résumé des Paiements :</strong>
        {{ $payments->where('status', 'completed')->count() }} terminés ({{ number_format($payments->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA) |
        {{ $payments->where('status', 'pending')->count() }} en attente ({{ number_format($payments->where('status', 'pending')->sum('amount'), 0, ',', ' ') }} FCFA) |
        {{ $payments->where('status', 'cancelled')->count() }} annulés ({{ number_format($payments->where('status', 'cancelled')->sum('amount'), 0, ',', ' ') }} FCFA)
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client / Pèlerin</th>
                <th>Campagne</th>
                <th>Montant</th>
                <th>Mode</th>
                <th>Référence</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>
                    @if($payment->pilgrim->client)
                        <strong>{{ $payment->pilgrim->client->full_name }}</strong><br>
                        <small style="color: #666;">Pèlerin: {{ $payment->pilgrim->full_name }}</small>
                    @else
                        {{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}<br>
                        <small style="color: #dc3545;">⚠️ Client non associé</small>
                    @endif
                </td>
                <td>{{ $payment->pilgrim->campaign->name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                <td class="text-center">
                    @switch($payment->payment_method)
                        @case('cash') Espèces @break
                        @case('check') Chèque @break
                        @case('bank_transfer') Virement @break
                        @case('card') Carte @break
                        @default {{ $payment->payment_method }}
                    @endswitch
                </td>
                <td>{{ $payment->reference ?? '-' }}</td>
                <td class="text-center status-{{ $payment->status }}">
                    @switch($payment->status)
                        @case('completed') TERMINÉ @break
                        @case('cancelled') ANNULÉ @break
                        @default EN ATTENTE
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }} - Système de Gestion</div>
        <div>{{ $agencySettings['company_phone'] ?? '' }} | {{ $agencySettings['company_email'] ?? '' }}</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>