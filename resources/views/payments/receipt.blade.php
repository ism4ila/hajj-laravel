<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu - {{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</title>
    <style>
        /* CSS SPÉCIFIQUE AU REÇU - Préfixe safir-receipt pour éviter conflits */
        .safir-receipt {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 8px;
            font-size: 8px;
            color: #333;
            line-height: 1.2;
            position: relative;
            background: #fff;
        }

        /* Watermark amélioré mais discret */
        .safir-receipt .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            opacity: 0.05;
            z-index: -1;
            max-width: 280px;
            max-height: 280px;
        }

        /* Header avec légère amélioration */
        .safir-receipt .receipt-header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            background: linear-gradient(to right, #f8f9fa, #fff, #f8f9fa);
        }

        .safir-receipt .header-left {
            display: table-cell;
            width: 42%;
            vertical-align: top;
            padding-right: 8px;
        }

        .safir-receipt .header-center {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .safir-receipt .header-right {
            display: table-cell;
            width: 25%;
            text-align: right;
            vertical-align: top;
            font-size: 7px;
        }

        /* Logo avec bordure améliorée */
        .safir-receipt .company-logo {
            max-height: 50px;
            max-width: 150px;
            margin-bottom: 4px;
            border: 2px solid #007bff;
            padding: 3px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,123,255,0.2);
        }

        .safir-receipt .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .safir-receipt .company-details {
            font-size: 7px;
            color: #666;
            line-height: 1.2;
        }

        /* Titre du document plus visible */
        .safir-receipt .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 4px;
            padding: 4px 8px;
            background: #e7f3ff;
            border-radius: 4px;
            display: inline-block;
        }

        .safir-receipt .receipt-number {
            font-size: 9px;
            color: #666;
            margin-top: 4px;
        }

        /* Zone "Servi par" améliorée */
        .safir-receipt .served-info {
            font-size: 7px;
            color: #555;
            border: 2px solid #28a745;
            padding: 6px;
            background: #f8fff9;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(40,167,69,0.2);
        }

        /* Contenu principal avec grid CSS simple */
        .safir-receipt .main-content {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .safir-receipt .left-column {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 10px;
        }

        .safir-receipt .right-column {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            padding-left: 10px;
            border-left: 2px solid #e9ecef;
        }

        /* Sections d'informations améliorées */
        .safir-receipt .info-section {
            margin-bottom: 8px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .safir-receipt .info-title {
            font-size: 10px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #007bff;
            padding-bottom: 3px;
            margin-bottom: 5px;
            position: relative;
        }

        /* Icônes avec pseudo-éléments pour éviter les dépendances */
        .safir-receipt .info-title.client::before {
            content: "👤 ";
            margin-right: 4px;
        }

        .safir-receipt .info-title.campaign::before {
            content: "🕌 ";
            margin-right: 4px;
        }

        .safir-receipt .info-title.payment::before {
            content: "💳 ";
            margin-right: 4px;
        }

        .safir-receipt .info-title.history::before {
            content: "📋 ";
            margin-right: 4px;
        }

        .safir-receipt .info-row {
            display: flex;
            margin-bottom: 3px;
            font-size: 8px;
            align-items: center;
        }

        .safir-receipt .info-label {
            width: 65px;
            font-weight: bold;
            color: #666;
            flex-shrink: 0;
        }

        .safir-receipt .info-value {
            flex: 1;
            color: #333;
            font-weight: 500;
        }

        /* Résumé de paiement mis en valeur */
        .safir-receipt .payment-summary {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            margin: 8px 0;
            box-shadow: 0 3px 10px rgba(40,167,69,0.3);
        }

        .safir-receipt .payment-amount {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .safir-receipt .payment-label {
            font-size: 8px;
            opacity: 0.9;
        }

        /* Mini résumé financier */
        .safir-receipt .financial-summary {
            display: table;
            width: 100%;
            margin-top: 8px;
            font-size: 7px;
        }

        .safir-receipt .financial-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }

        .safir-receipt .financial-item:not(:last-child) {
            margin-right: 2px;
        }

        .safir-receipt .financial-value {
            font-weight: bold;
            font-size: 8px;
        }

        /* Tableau amélioré */
        .safir-receipt .payments-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 4px 0;
            font-size: 7px;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .safir-receipt .payments-table th {
            background: linear-gradient(135deg, #495057, #6c757d);
            color: white;
            font-weight: bold;
            padding: 4px 2px;
            border: none;
            text-align: center;
            font-size: 7px;
        }

        .safir-receipt .payments-table td {
            padding: 3px 2px;
            border: none;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
            background: white;
        }

        .safir-receipt .payments-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        /* Ligne du paiement actuel */
        .safir-receipt .current-payment td {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
            font-weight: bold;
            border-left: 3px solid #ffc107;
            color: #856404;
        }

        /* Badges de statut améliorés */
        .safir-receipt .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .safir-receipt .status-completed {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .safir-receipt .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .safir-receipt .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Section signatures */
        .safir-receipt .signature-section {
            margin-top: 10px;
            display: table;
            width: 100%;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .safir-receipt .signature-left,
        .safir-receipt .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 10px;
        }

        .safir-receipt .signature-box {
            border: 2px dashed #dee2e6;
            padding: 12px;
            border-radius: 6px;
            background: white;
        }

        .safir-receipt .signature-line {
            border-bottom: 2px solid #333;
            margin-bottom: 4px;
            height: 25px;
        }

        .safir-receipt .signature-label {
            font-size: 8px;
            color: #666;
            font-weight: bold;
        }

        /* Footer amélioré */
        .safir-receipt .receipt-footer {
            margin-top: 10px;
            padding: 8px;
            border-top: 2px solid #007bff;
            background: linear-gradient(to right, #f8f9fa, #fff, #f8f9fa);
            text-align: center;
            color: #666;
            font-size: 7px;
            line-height: 1.3;
            border-radius: 0 0 6px 6px;
        }

        .safir-receipt .footer-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }

        /* Note d'aide pour l'historique */
        .safir-receipt .table-note {
            font-size: 6px;
            color: #666;
            margin-top: 4px;
            text-align: center;
            font-style: italic;
        }

        /* Responsive pour impression */
        @media print {
            .safir-receipt {
                padding: 0;
            }
            .safir-receipt .watermark {
                opacity: 0.02;
            }
        }
    </style>
</head>
<body class="safir-receipt">
    @php
        $currency = $agencySettings['currency_symbol'] ?? $agencySettings['default_currency'] ?? 'FCFA';
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

    {{-- Header amélioré --}}
    <div class="receipt-header">
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
                    <img src="{{ $logoSrc }}" alt="Logo" class="company-logo">
                @endif
            @endif
            <div class="company-name">{{ $agencySettings['company_name'] ?? 'SAFIR' }}</div>
            <div class="company-details">
                @if($agencySettings['company_address'])📍 {{ $agencySettings['company_address'] }}, @endif
                @if($agencySettings['company_city']){{ $agencySettings['company_city'] }}@endif<br>
                📞 {{ $agencySettings['company_phone'] ?? 'N/A' }}
                @if($agencySettings['company_email']) | 📧 {{ $agencySettings['company_email'] }}@endif
            </div>
        </div>

        <div class="header-center">
            <div class="receipt-title">REÇU DE PAIEMENT</div>
            <div class="receipt-number">
                <strong>Client:</strong> {{ $payment->pilgrim->client->full_name ?? $payment->pilgrim->full_name }}<br>
                <strong>N°</strong> {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="header-right">
            <div class="served-info">
                <strong>✅ Servi par:</strong> {{ $servingUser->name }}<br>
                <strong>📅 Le:</strong> {{ now()->format('d/m/Y H:i') }}
                @if($agencySettings['company_registration'])
                    <br><strong>📋 N° Enreg:</strong> {{ $agencySettings['company_registration'] }}
                @endif
            </div>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="main-content">
        <div class="left-column">
            {{-- Informations client --}}
            <div class="info-section">
                <div class="info-title client">CLIENT & PÈLERIN</div>
                <div class="info-row">
                    <div class="info-label">Nom:</div>
                    <div class="info-value"><strong>{{ $payment->pilgrim->full_name }}</strong></div>
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
                <div class="info-row">
                    <div class="info-label">Catégorie:</div>
                    <div class="info-value">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</div>
                </div>
            </div>

            {{-- Informations campagne --}}
            @if($payment->pilgrim->campaign)
            <div class="info-section">
                <div class="info-title campaign">CAMPAGNE</div>
                <div class="info-row">
                    <div class="info-label">Nom:</div>
                    <div class="info-value">{{ $payment->pilgrim->campaign->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Type:</div>
                    <div class="info-value">{{ ucfirst($payment->pilgrim->campaign->type) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Période:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
                </div>
            </div>
            @endif

            {{-- Résumé de paiement --}}
            <div class="payment-summary">
                <div class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $currency }}</div>
                <div class="payment-label">MONTANT DE CE PAIEMENT</div>
                
                <div class="financial-summary">
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</div>
                        <div>Total dû</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</div>
                        <div>Payé</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</div>
                        <div>Restant</div>
                    </div>
                </div>
            </div>

            {{-- Détails du paiement --}}
            <div class="info-section">
                <div class="info-title payment">DÉTAILS PAIEMENT</div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mode:</div>
                    <div class="info-value">
                        @switch($payment->payment_method)
                            @case('cash') 💵 Espèces @break
                            @case('check') 📋 Chèque @break
                            @case('bank_transfer') 🏦 Virement @break
                            @case('card') 💳 Carte @break
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
                                @case('completed') ✅ Validé @break
                                @case('cancelled') ❌ Annulé @break
                                @default ⏳ En attente
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-column">
            {{-- Historique des paiements --}}
            <div class="info-section">
                <div class="info-title history">HISTORIQUE PAIEMENTS</div>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments->take(8) as $paymentItem)
                        <tr class="{{ $paymentItem->id == $payment->id ? 'current-payment' : '' }}">
                            <td>{{ \Carbon\Carbon::parse($paymentItem->payment_date)->format('d/m') }}</td>
                            <td>{{ number_format($paymentItem->amount, 0, ',', ' ') }}</td>
                            <td>
                                @switch($paymentItem->payment_method)
                                    @case('cash') ESP @break
                                    @case('check') CHQ @break
                                    @case('bank_transfer') VIR @break
                                    @case('card') CB @break
                                    @default {{ substr($paymentItem->payment_method, 0, 3) }}
                                @endswitch
                            </td>
                            <td>
                                <span class="status-badge status-{{ $paymentItem->status }}">
                                    @switch($paymentItem->status)
                                        @case('completed') ✅ @break
                                        @case('cancelled') ❌ @break
                                        @default ⏳
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="table-note">
                    💡 Ligne surlignée = paiement actuel | Affichage des 8 derniers paiements
                </div>
            </div>
        </div>
    </div>

    {{-- Section signatures --}}
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">✍️ Signature Client</div>
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">🏢 Cachet Agence</div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="receipt-footer">
        <div class="footer-title">{{ $agencySettings['company_name'] ?? 'SAFIR' }} - Reçu généré le {{ now()->format('d/m/Y H:i') }}</div>
        @if($agencySettings['legal_notice'])
            <div>{{ $agencySettings['legal_notice'] }}</div>
        @endif
        <div>⚠️ Pour toute réclamation, présenter ce reçu | 📞 Contact: {{ $agencySettings['company_phone'] ?? 'N/A' }}</div>
    </div>
</body>
</html>