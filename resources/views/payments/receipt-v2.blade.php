<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu V2 - {{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</title>
    <style>
        /* VERSION 2 DU REÇU - Design moderne et minimaliste */
        .receipt-v2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #2c3e50;
            line-height: 1.5;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Container principal */
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        /* Header moderne */
        .modern-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .modern-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }

        .header-content {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 30px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .company-info {
            text-align: left;
        }

        .company-logo-v2 {
            max-height: 80px;
            max-width: 200px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .company-name-v2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .company-details-v2 {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .receipt-title-v2 {
            text-align: center;
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .title-main {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title-sub {
            font-size: 16px;
            opacity: 0.8;
        }

        .receipt-meta {
            text-align: right;
            font-size: 14px;
        }

        .meta-item {
            margin-bottom: 8px;
            padding: 8px 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }

        /* Corps principal */
        .receipt-body {
            padding: 40px;
        }

        /* Carte de paiement principale */
        .payment-highlight {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(17, 153, 142, 0.3);
            position: relative;
            overflow: hidden;
        }

        .payment-highlight::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        .amount-display {
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 10px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .amount-label {
            font-size: 18px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
        }

        /* Grille d'informations */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border-left: 5px solid #667eea;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-icon {
            font-size: 24px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label-v2 {
            font-weight: 600;
            color: #6c757d;
        }

        .info-value-v2 {
            font-weight: 700;
            color: #2c3e50;
            text-align: right;
        }

        /* Résumé financier */
        .financial-overview {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(252, 182, 159, 0.3);
        }

        .financial-title {
            font-size: 20px;
            font-weight: 700;
            color: #8b4513;
            margin-bottom: 20px;
            text-align: center;
        }

        .financial-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .financial-item {
            text-align: center;
            background: rgba(255,255,255,0.7);
            padding: 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .financial-amount {
            font-size: 24px;
            font-weight: 800;
            color: #8b4513;
            margin-bottom: 5px;
        }

        .financial-label {
            font-size: 14px;
            color: #a0522d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Tableau moderne */
        .modern-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .payments-table-v2 {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table-v2 th {
            background: #f8f9fa;
            padding: 15px 12px;
            font-weight: 600;
            color: #495057;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }

        .payments-table-v2 td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #f1f3f4;
        }

        .payments-table-v2 tr:hover {
            background-color: #f8f9fa;
        }

        .current-payment-v2 {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            font-weight: 700;
            color: #856404;
        }

        .current-payment-v2 td {
            border-left: 4px solid #ffc107;
        }

        /* Badges modernes */
        .status-badge-v2 {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed-v2 {
            background: linear-gradient(135deg, #d4edda, #a3d5a3);
            color: #155724;
        }

        .status-pending-v2 {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .status-cancelled-v2 {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Section signatures moderne */
        .signature-area {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .signature-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }

        .signature-space {
            height: 80px;
            border-bottom: 2px solid #6c757d;
            margin-bottom: 15px;
            position: relative;
        }

        .signature-label-v2 {
            font-weight: 600;
            color: #495057;
            font-size: 16px;
        }

        /* Footer moderne */
        .modern-footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 25px;
            text-align: center;
            margin-top: 30px;
        }

        .footer-content {
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-highlight {
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Responsive amélioré */
        @media (max-width: 992px) {
            .receipt-v2 {
                padding: 15px;
            }

            .header-content {
                grid-template-columns: 1fr auto 1fr;
                gap: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .receipt-v2 {
                padding: 10px;
            }

            .header-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 15px;
            }

            .receipt-body {
                padding: 20px;
            }

            .financial-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .signature-area {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .amount-display {
                font-size: 36px;
            }

            .payments-table-v2 {
                font-size: 12px;
            }

            .payments-table-v2 th,
            .payments-table-v2 td {
                padding: 8px 4px;
            }
        }

        @media (max-width: 576px) {
            .receipt-v2 {
                padding: 8px;
            }

            .receipt-body {
                padding: 15px;
            }

            .amount-display {
                font-size: 28px;
            }

            .financial-amount {
                font-size: 18px;
            }

            .info-card {
                padding: 15px;
            }

            .card-title {
                font-size: 16px;
            }

            .payments-table-v2 {
                font-size: 10px;
            }

            .payments-table-v2 th,
            .payments-table-v2 td {
                padding: 6px 2px;
            }

            /* Table horizontale pour mobile */
            .modern-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .payments-table-v2 {
                min-width: 500px;
            }
        }

        @media (max-width: 480px) {
            .receipt-v2 {
                padding: 5px;
            }

            .receipt-body {
                padding: 10px;
            }

            .amount-display {
                font-size: 24px;
            }

            .info-card {
                padding: 10px;
            }

            .financial-item {
                padding: 15px 10px;
            }
        }

        /* Impression */
        @media print {
            .receipt-v2 {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }

            .modern-header,
            .payment-highlight,
            .financial-overview {
                background: #f8f9fa !important;
                color: #333 !important;
            }
        }
    </style>
</head>
<body class="receipt-v2">
    @php
        $currency = $agencySettings['currency_symbol'] ?? $agencySettings['default_currency'] ?? 'FCFA';
    @endphp

    <div class="receipt-container">
        {{-- Header moderne --}}
        <div class="modern-header">
            <div class="header-content">
                <div class="company-info">
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
                            <img src="{{ $logoSrc }}" alt="Logo" class="company-logo-v2">
                        @endif
                    @endif
                    <div class="company-name-v2">{{ $agencySettings['company_name'] ?? 'SAFIR' }}</div>
                    <div class="company-details-v2">
                        @if($agencySettings['company_address'])
                            📍 {{ $agencySettings['company_address'] }}
                            @if($agencySettings['company_city']), {{ $agencySettings['company_city'] }}@endif
                            <br>
                        @endif
                        📞 {{ $agencySettings['company_phone'] ?? 'N/A' }}
                        @if($agencySettings['company_email'])
                            <br>📧 {{ $agencySettings['company_email'] }}
                        @endif
                    </div>
                </div>

                <div class="receipt-title-v2">
                    <div class="title-main">Reçu de Paiement</div>
                    <div class="title-sub">{{ $payment->pilgrim->client->full_name ?? $payment->pilgrim->full_name }}</div>
                </div>

                <div class="receipt-meta">
                    <div class="meta-item">
                        <strong>N° Reçu:</strong><br>
                        {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="meta-item">
                        <strong>Date d'émission:</strong><br>
                        {{ now()->format('d/m/Y H:i') }}
                    </div>
                    <div class="meta-item">
                        <strong>Agent:</strong><br>
                        {{ $servingUser->name }}
                    </div>
                    @if($agencySettings['company_registration'])
                    <div class="meta-item">
                        <strong>N° Enregistrement:</strong><br>
                        {{ $agencySettings['company_registration'] }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="receipt-body">
            {{-- Montant principal --}}
            <div class="payment-highlight">
                <div class="amount-display">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $currency }}</div>
                <div class="amount-label">Montant de ce paiement</div>
            </div>

            {{-- Grille d'informations --}}
            <div class="info-grid">
                {{-- Informations client --}}
                <div class="info-card">
                    <div class="card-title">
                        <span class="card-icon">👤</span>
                        Informations Client
                    </div>
                    <div class="info-item">
                        <span class="info-label-v2">Nom complet:</span>
                        <span class="info-value-v2">{{ $payment->pilgrim->full_name }}</span>
                    </div>
                    @if($payment->pilgrim->email)
                    <div class="info-item">
                        <span class="info-label-v2">Email:</span>
                        <span class="info-value-v2">{{ $payment->pilgrim->email }}</span>
                    </div>
                    @endif
                    @if($payment->pilgrim->phone)
                    <div class="info-item">
                        <span class="info-label-v2">Téléphone:</span>
                        <span class="info-value-v2">{{ $payment->pilgrim->phone }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label-v2">Catégorie:</span>
                        <span class="info-value-v2">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</span>
                    </div>
                </div>

                {{-- Détails du paiement --}}
                <div class="info-card">
                    <div class="card-title">
                        <span class="card-icon">💳</span>
                        Détails du Paiement
                    </div>
                    <div class="info-item">
                        <span class="info-label-v2">Date de paiement:</span>
                        <span class="info-value-v2">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label-v2">Mode de paiement:</span>
                        <span class="info-value-v2">
                            @switch($payment->payment_method)
                                @case('cash') 💵 Espèces @break
                                @case('check') 📋 Chèque @break
                                @case('bank_transfer') 🏦 Virement bancaire @break
                                @case('card') 💳 Carte bancaire @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </span>
                    </div>
                    @if($payment->reference)
                    <div class="info-item">
                        <span class="info-label-v2">Référence:</span>
                        <span class="info-value-v2">{{ $payment->reference }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label-v2">Statut:</span>
                        <span class="info-value-v2">
                            <span class="status-badge-v2 status-{{ $payment->status }}-v2">
                                @switch($payment->status)
                                    @case('completed') ✅ Validé @break
                                    @case('cancelled') ❌ Annulé @break
                                    @default ⏳ En attente
                                @endswitch
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Informations campagne si disponible --}}
            @if($payment->pilgrim->campaign)
            <div class="info-card" style="margin-bottom: 30px;">
                <div class="card-title">
                    <span class="card-icon">🕌</span>
                    Informations Campagne
                </div>
                <div class="info-item">
                    <span class="info-label-v2">Nom de la campagne:</span>
                    <span class="info-value-v2">{{ $payment->pilgrim->campaign->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label-v2">Type:</span>
                    <span class="info-value-v2">{{ ucfirst($payment->pilgrim->campaign->type) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label-v2">Période:</span>
                    <span class="info-value-v2">
                        {{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}
                        →
                        {{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            @endif

            {{-- Résumé financier --}}
            <div class="financial-overview">
                <div class="financial-title">Résumé Financier</div>
                <div class="financial-grid">
                    <div class="financial-item">
                        <div class="financial-amount">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Total à payer</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-amount">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Total payé</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-amount">{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Montant restant</div>
                    </div>
                </div>
            </div>

            {{-- Historique des paiements --}}
            <div class="modern-table">
                <div class="table-header">
                    <h3 class="table-title">📋 Historique des Paiements</h3>
                </div>
                <table class="payments-table-v2">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant ({{ $currency }})</th>
                            <th>Mode de paiement</th>
                            <th>Référence</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments->take(10) as $paymentItem)
                        <tr class="{{ $paymentItem->id == $payment->id ? 'current-payment-v2' : '' }}">
                            <td>{{ \Carbon\Carbon::parse($paymentItem->payment_date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($paymentItem->amount, 0, ',', ' ') }}</td>
                            <td>
                                @switch($paymentItem->payment_method)
                                    @case('cash') 💵 Espèces @break
                                    @case('check') 📋 Chèque @break
                                    @case('bank_transfer') 🏦 Virement @break
                                    @case('card') 💳 Carte @break
                                    @default {{ $paymentItem->payment_method }}
                                @endswitch
                            </td>
                            <td>{{ $paymentItem->reference ?? '-' }}</td>
                            <td>
                                <span class="status-badge-v2 status-{{ $paymentItem->status }}-v2">
                                    @switch($paymentItem->status)
                                        @case('completed') ✅ Validé @break
                                        @case('cancelled') ❌ Annulé @break
                                        @default ⏳ En attente
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Section signatures --}}
            <div class="signature-area">
                <div class="signature-card">
                    <div class="signature-space"></div>
                    <div class="signature-label-v2">✍️ Signature du Client</div>
                </div>
                <div class="signature-card">
                    <div class="signature-space"></div>
                    <div class="signature-label-v2">🏢 Cachet de l'Agence</div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="modern-footer">
            <div class="footer-content">
                <div class="footer-highlight">
                    {{ $agencySettings['company_name'] ?? 'SAFIR' }} - Reçu généré le {{ now()->format('d/m/Y à H:i') }}
                </div>
                @if($agencySettings['legal_notice'])
                <div>{{ $agencySettings['legal_notice'] }}</div>
                @endif
                <div>
                    ⚠️ Conservez ce reçu pour toute réclamation |
                    📞 Service client: {{ $agencySettings['company_phone'] ?? 'N/A' }}
                    @if($agencySettings['company_email'])
                     | 📧 {{ $agencySettings['company_email'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>