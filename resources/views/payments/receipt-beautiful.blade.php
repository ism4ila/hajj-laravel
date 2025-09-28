<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu Complet - {{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 9px;
            color: #333;
            line-height: 1.2;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            opacity: 0.1;
            z-index: -1;
            max-width: 300px;
            max-height: 300px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
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
            font-size: 9px;
        }

        .logo {
            max-height: 60px;
            max-width: 180px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            padding: 3px;
            border-radius: 5px;
            background: rgba(255,255,255,0.8);
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }

        .company-info {
            font-size: 9px;
            color: #666;
            line-height: 1.2;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .receipt-number {
            font-size: 12px;
            color: #666;
        }

        .served-by {
            font-size: 9px;
            color: #666;
            border: 1px solid #ddd;
            padding: 8px;
            background: #f8f9fa;
        }

        .main-content {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .left-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .right-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 15px;
            border-left: 1px solid #eee;
        }

        .info-section {
            margin-bottom: 8px;
        }

        .info-title {
            font-size: 11px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
            font-size: 9px;
        }

        .info-label {
            width: 80px;
            font-weight: bold;
            color: #666;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .payment-summary {
            background-color: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
        }

        .total-amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }

        .currency {
            font-size: 11px;
            color: #666;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 7px;
        }

        .payments-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
            padding: 4px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .payments-table td {
            padding: 3px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-left {
            display: table-cell;
            width: 40%;
            text-align: center;
        }

        .signature-right {
            display: table-cell;
            width: 60%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 3px;
            height: 30px;
        }

        .signature-label {
            font-size: 9px;
            color: #666;
        }

        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 8px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .highlight-payment {
            background-color: #fff3cd !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    {{-- Logo Watermark (Filigrane) --}}
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

    {{-- Header with Agency Info, Logo and Serving User --}}
    <div class="header">
        <div class="header-left">
            @if($agencySettings['company_logo'] ?? null)
                @php
                    $logoPath = storage_path('app/public/logos/' . $agencySettings['company_logo']);
                    $logoExists = file_exists($logoPath);
                    if ($logoExists) {
                        $logoContent = file_get_contents($logoPath);
                        $logoBase64 = base64_encode($logoContent);
                        $logoExtension = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $logoSrc = 'data:image/' . $logoExtension . ';base64,' . $logoBase64;
                    }
                @endphp

                @if(isset($logoExists) && $logoExists)
                    <img src="{{ $logoSrc }}" alt="Logo" class="logo">
                @endif
            @endif
            <div class="company-name">{{ $agencySettings['company_name'] ?? config('app.name', 'Hajj Management') }}</div>
            @if($agencySettings['company_slogan'] ?? null)
                <div class="company-info" style="font-style: italic;">{{ $agencySettings['company_slogan'] }}</div>
            @endif
            <div class="company-info">
                @if($agencySettings['company_address'] ?? null){{ $agencySettings['company_address'] }}@endif<br>
                @if($agencySettings['company_city'] ?? null){{ $agencySettings['company_city'] }}, @endif
                @if($agencySettings['company_country'] ?? null){{ $agencySettings['company_country'] }}@endif<br>
                @if($agencySettings['company_phone'] ?? null)Tél: {{ $agencySettings['company_phone'] }}@endif<br>
                @if($agencySettings['company_email'] ?? null)Email: {{ $agencySettings['company_email'] }}@endif<br>
                @if($agencySettings['company_website'] ?? null)Web: {{ $agencySettings['company_website'] }}@endif
            </div>
        </div>

        <div class="header-center">
            <div class="document-title">REÇU COMPLET DE PAIEMENT</div>
            <div class="receipt-number">
                @if($payment->pilgrim->client)
                    Client: {{ $payment->pilgrim->client->full_name }}
                @else
                    Client: {{ $payment->pilgrim->full_name }}
                @endif
            </div>
            <div class="receipt-number">Reçu N° {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="header-right">
            <div class="served-by">
                <strong>Servi par:</strong><br>
                {{ $servingUser->name }}<br>
                {{ $servingUser->email }}<br>
                Le: {{ now()->format('d/m/Y à H:i') }}
                @if($agencySettings['company_registration'] ?? null)
                    <br><br><strong>N° Enreg:</strong><br>{{ $agencySettings['company_registration'] }}
                @endif
                @if($agencySettings['company_license'] ?? null)
                    <br><strong>Licence:</strong><br>{{ $agencySettings['company_license'] }}
                @endif
            </div>
        </div>
    </div>

    {{-- Main Content in Two Columns --}}
    <div class="main-content">
        <div class="left-column">
            {{-- Client Information --}}
            <div class="info-section">
                <div class="info-title">INFORMATIONS CLIENT</div>
                @if($payment->pilgrim->client)
                    <div class="info-row">
                        <div class="info-label">Client:</div>
                        <div class="info-value"><strong>{{ $payment->pilgrim->client->full_name }}</strong></div>
                    </div>
                    @if($payment->pilgrim->client->email)
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $payment->pilgrim->client->email }}</div>
                    </div>
                    @endif
                    @if($payment->pilgrim->client->phone)
                    <div class="info-row">
                        <div class="info-label">Téléphone:</div>
                        <div class="info-value">{{ $payment->pilgrim->client->phone }}</div>
                    </div>
                    @endif
                    @if($payment->pilgrim->client->nationality)
                    <div class="info-row">
                        <div class="info-label">Nationalité:</div>
                        <div class="info-value">{{ $payment->pilgrim->client->nationality }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Pèlerin:</div>
                        <div class="info-value">{{ $payment->pilgrim->full_name }}</div>
                    </div>
                @else
                    <div class="info-row">
                        <div class="info-label">Nom:</div>
                        <div class="info-value">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</div>
                    </div>
                    @if($payment->pilgrim->email)
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $payment->pilgrim->email }}</div>
                    </div>
                    @endif
                    @if($payment->pilgrim->phone)
                    <div class="info-row">
                        <div class="info-label">Téléphone:</div>
                        <div class="info-value">{{ $payment->pilgrim->phone }}</div>
                    </div>
                    @endif
                    <div style="color: #dc3545; font-size: 8px; font-style: italic;">⚠️ Client non associé</div>
                @endif
                <div class="info-row">
                    <div class="info-label">Catégorie:</div>
                    <div class="info-value">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</div>
                </div>
            </div>

            {{-- Campaign Information --}}
            @if($payment->pilgrim->campaign)
            <div class="info-section">
                <div class="info-title">CAMPAGNE</div>
                <div class="info-row">
                    <div class="info-label">Nom:</div>
                    <div class="info-value">{{ $payment->pilgrim->campaign->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Type:</div>
                    <div class="info-value">{{ ucfirst($payment->pilgrim->campaign->type) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Départ:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Retour:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
                </div>
            </div>
            @endif

            {{-- Payment Summary --}}
            <div class="payment-summary">
                <div class="info-title">RÉSUMÉ FINANCIER</div>
                <div class="info-row">
                    <div class="info-label">Total voyage:</div>
                    <div class="info-value">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }} {{ $agencySettings['default_currency'] ?? 'FCFA' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Total payé:</div>
                    <div class="info-value">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }} {{ $agencySettings['default_currency'] ?? 'FCFA' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Restant:</div>
                    <div class="info-value"><strong>{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }} {{ $agencySettings['default_currency'] ?? 'FCFA' }}</strong></div>
                </div>
                <div class="total-amount">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }} {{ $agencySettings['default_currency'] ?? 'FCFA' }}</div>
                <div class="currency">Total Payé à ce jour</div>
            </div>
        </div>

        <div class="right-column">
            {{-- Complete Payment History --}}
            <div class="info-section">
                <div class="info-title">HISTORIQUE COMPLET DES PAIEMENTS</div>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Référence</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments as $paymentItem)
                        <tr class="{{ $paymentItem->id == $payment->id ? 'highlight-payment' : '' }}">
                            <td>{{ \Carbon\Carbon::parse($paymentItem->payment_date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($paymentItem->amount, 0, ',', ' ') }}</td>
                            <td>
                                @switch($paymentItem->payment_method)
                                    @case('cash') Espèces @break
                                    @case('check') Chèque @break
                                    @case('bank_transfer') Virement @break
                                    @case('card') Carte @break
                                    @default {{ $paymentItem->payment_method }}
                                @endswitch
                            </td>
                            <td>{{ $paymentItem->reference ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $paymentItem->status }}">
                                    @switch($paymentItem->status)
                                        @case('completed') OK @break
                                        @case('cancelled') ANN @break
                                        @default ATT
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="font-size: 7px; margin-top: 5px; color: #666;">
                    <strong>Légende:</strong> OK=Terminé, ATT=En attente, ANN=Annulé |
                    La ligne en surbrillance correspond au paiement de ce reçu
                </div>
            </div>

            {{-- Current Payment Details --}}
            <div class="info-section">
                <div class="info-title">DÉTAILS DU PAIEMENT ACTUEL (N°{{ $payment->id }})</div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Montant:</div>
                    <div class="info-value"><strong>{{ number_format($payment->amount, 0, ',', ' ') }} {{ $agencySettings['default_currency'] ?? 'FCFA' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mode:</div>
                    <div class="info-value">
                        @switch($payment->payment_method)
                            @case('cash') Espèces @break
                            @case('check') Chèque @break
                            @case('bank_transfer') Virement bancaire @break
                            @case('card') Carte bancaire @break
                            @default {{ $payment->payment_method }}
                        @endswitch
                    </div>
                </div>
                @if($payment->reference)
                <div class="info-row">
                    <div class="info-label">Référence:</div>
                    <div class="info-value">{{ $payment->reference }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $payment->status }}">
                            @switch($payment->status)
                                @case('completed') TERMINÉ @break
                                @case('cancelled') ANNULÉ @break
                                @default EN ATTENTE
                            @endswitch
                        </span>
                    </div>
                </div>
                @if($payment->notes)
                <div class="info-row">
                    <div class="info-label">Notes:</div>
                    <div class="info-value">{{ $payment->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-line"></div>
            <div class="signature-label">Signature du Client</div>
        </div>
        <div class="signature-right">
            <div class="signature-line"></div>
            <div class="signature-label">Cachet et Signature de l'Agence</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div><strong>{{ $agencySettings['company_name'] ?? config('app.name', 'Hajj Management') }}</strong> - Système de Gestion des Pèlerins</div>
        <div>Reçu généré le {{ now()->format('d/m/Y à H:i') }} par {{ $servingUser->name }}</div>
        <div>Pour toute réclamation, veuillez présenter ce reçu complet.</div>
        @if($agencySettings['company_phone'] ?? null)
            <div>Contact: {{ $agencySettings['company_phone'] }} | {{ $agencySettings['company_email'] ?? '' }}</div>
        @endif
    </div>
</body>
</html>