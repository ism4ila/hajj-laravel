<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif Client - {{ $payment->pilgrim->full_name }}</title>
    <style>
        /* PAGE RÉCAPITULATIVE SIMPLE ET CLAIRE */
        .client-summary {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 30px;
            background: #f5f6fa;
            min-height: 100vh;
            color: #2f3542;
        }

        .summary-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* HEADER AVEC NOM CLIENT TRÈS VISIBLE */
        .client-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 30px;
            position: relative;
        }

        .client-name {
            font-size: 42px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .client-subtitle {
            font-size: 18px;
            opacity: 0.9;
            font-weight: 400;
            letter-spacing: 1px;
        }

        .header-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24, #6c5ce7);
        }

        /* SECTION MONTANTS TRÈS VISIBLE */
        .amounts-section {
            padding: 50px 40px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            text-align: center;
            color: white;
        }

        .amounts-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .amounts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .amount-card {
            background: rgba(255,255,255,0.95);
            color: #2f3542;
            padding: 30px 25px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(0);
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .amount-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .amount-card.total {
            border-color: #ff6b6b;
            background: linear-gradient(135deg, #fff5f5, #ffe0e0);
        }

        .amount-card.paid {
            border-color: #4ecdc4;
            background: linear-gradient(135deg, #f0fdfc, #e0f9f6);
        }

        .amount-card.remaining {
            border-color: #f9ca24;
            background: linear-gradient(135deg, #fffdf0, #fff8e0);
        }

        .amount-label {
            font-size: 16px;
            font-weight: 600;
            color: #576574;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: 900;
            margin-bottom: 10px;
            line-height: 1;
        }

        .amount-card.total .amount-value {
            color: #ff6b6b;
        }

        .amount-card.paid .amount-value {
            color: #4ecdc4;
        }

        .amount-card.remaining .amount-value {
            color: #f9ca24;
        }

        .amount-currency {
            font-size: 18px;
            font-weight: 600;
            color: #8395a7;
        }

        /* PAIEMENT ACTUEL EN ÉVIDENCE */
        .current-payment {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 40px;
            margin: 30px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(17, 153, 142, 0.3);
        }

        .payment-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .payment-amount {
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 15px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.3);
        }

        .payment-date {
            font-size: 18px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* INFORMATIONS COMPLÉMENTAIRES */
        .info-section {
            padding: 40px;
            background: #f8f9fa;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
        }

        .info-title {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
        }

        .info-value {
            font-weight: 700;
            color: #2f3542;
            text-align: right;
            font-size: 14px;
        }

        /* BARRE DE PROGRESSION */
        .progress-section {
            padding: 30px 40px;
            background: white;
        }

        .progress-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #2f3542;
            margin-bottom: 30px;
        }

        .progress-bar-container {
            background: #e9ecef;
            border-radius: 50px;
            height: 20px;
            overflow: hidden;
            position: relative;
            margin-bottom: 15px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            border-radius: 50px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-text {
            text-align: center;
            font-weight: 600;
            color: #576574;
            font-size: 16px;
        }

        /* STATUT DE PAIEMENT */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-completed {
            background: linear-gradient(135deg, #d4edda, #a3d5a3);
            color: #155724;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* FOOTER */
        .summary-footer {
            background: #2f3542;
            color: white;
            text-align: center;
            padding: 25px;
            font-size: 14px;
        }

        .footer-company {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 16px;
        }

        /* RESPONSIVE AMÉLIORÉ */
        @media (max-width: 992px) {
            .client-summary {
                padding: 20px;
            }

            .amounts-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }

        @media (max-width: 768px) {
            .client-summary {
                padding: 15px;
            }

            .client-name {
                font-size: 32px;
                letter-spacing: 2px;
            }

            .client-subtitle {
                font-size: 16px;
            }

            .amount-value {
                font-size: 28px;
            }

            .payment-amount {
                font-size: 36px;
            }

            .amounts-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .current-payment {
                margin: 20px;
                padding: 30px 20px;
            }

            .amounts-section {
                padding: 40px 30px;
            }

            .info-section {
                padding: 30px;
            }
        }

        @media (max-width: 576px) {
            .client-summary {
                padding: 10px;
            }

            .client-header {
                padding: 30px 20px;
            }

            .client-name {
                font-size: 24px;
                letter-spacing: 1px;
            }

            .client-subtitle {
                font-size: 14px;
            }

            .amounts-section {
                padding: 30px 20px;
            }

            .amounts-title {
                font-size: 22px;
                margin-bottom: 30px;
            }

            .amount-value {
                font-size: 24px;
            }

            .payment-amount {
                font-size: 32px;
            }

            .payment-title {
                font-size: 20px;
            }

            .current-payment {
                margin: 15px 10px;
                padding: 25px 15px;
            }

            .info-section {
                padding: 20px;
            }

            .info-card {
                padding: 20px;
            }

            .info-title {
                font-size: 16px;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-value {
                text-align: left;
                font-size: 14px;
            }

            .progress-section {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .client-summary {
                padding: 8px;
            }

            .client-header {
                padding: 25px 15px;
            }

            .client-name {
                font-size: 20px;
                letter-spacing: 0.5px;
            }

            .amounts-section {
                padding: 25px 15px;
            }

            .amount-value {
                font-size: 20px;
            }

            .payment-amount {
                font-size: 28px;
            }

            .current-payment {
                margin: 10px 5px;
                padding: 20px 10px;
            }

            .info-section {
                padding: 15px;
            }

            .info-card {
                padding: 15px;
            }

            .amount-card {
                padding: 20px 15px;
            }

            .amount-label {
                font-size: 14px;
            }
        }

        /* Touch-friendly pour mobile */
        @media (max-width: 768px) {
            .amount-card {
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                transition: transform 0.2s ease;
            }

            .amount-card:active {
                transform: scale(0.98);
            }

            .status-badge {
                padding: 12px 18px;
                font-size: 12px;
            }
        }

        /* IMPRESSION */
        @media print {
            .client-summary {
                background: white;
                padding: 0;
            }

            .summary-container {
                box-shadow: none;
                border-radius: 0;
            }

            .client-header,
            .amounts-section,
            .current-payment {
                background: #f8f9fa !important;
                color: #333 !important;
            }

            .amount-card {
                border: 2px solid #dee2e6 !important;
                background: white !important;
            }
        }
    </style>
</head>
<body class="client-summary">
    @php
        $currency = $agencySettings['currency_symbol'] ?? $agencySettings['default_currency'] ?? 'FCFA';
        $progressPercentage = $payment->pilgrim->total_amount > 0 ?
            ($payment->pilgrim->paid_amount / $payment->pilgrim->total_amount) * 100 : 0;
    @endphp

    <div class="summary-container">
        {{-- Header avec nom client très visible --}}
        <div class="client-header">
            <div class="header-decoration"></div>
            <div class="client-name">{{ $payment->pilgrim->full_name }}</div>
            <div class="client-subtitle">
                @if($payment->pilgrim->campaign)
                    {{ $payment->pilgrim->campaign->name }} - {{ ucfirst($payment->pilgrim->campaign->type) }}
                @else
                    Client {{ ucfirst($payment->pilgrim->category ?? 'Standard') }}
                @endif
            </div>
        </div>

        {{-- Section montants très visibles --}}
        <div class="amounts-section">
            <div class="amounts-title">💰 Situation Financière</div>
            <div class="amounts-grid">
                <div class="amount-card total">
                    <div class="amount-label">📊 Total à Payer</div>
                    <div class="amount-value">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</div>
                    <div class="amount-currency">{{ $currency }}</div>
                </div>

                <div class="amount-card paid">
                    <div class="amount-label">✅ Total Payé</div>
                    <div class="amount-value">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</div>
                    <div class="amount-currency">{{ $currency }}</div>
                </div>

                <div class="amount-card remaining">
                    <div class="amount-label">⏳ Montant Restant</div>
                    <div class="amount-value">{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</div>
                    <div class="amount-currency">{{ $currency }}</div>
                </div>
            </div>
        </div>

        {{-- Paiement actuel en évidence --}}
        <div class="current-payment">
            <div class="payment-title">🎯 Paiement du Jour</div>
            <div class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $currency }}</div>
            <div class="payment-date">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y à H:i') }}</div>
        </div>

        {{-- Barre de progression --}}
        <div class="progress-section">
            <div class="progress-title">📈 Progression du Paiement</div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
            </div>
            <div class="progress-text">{{ number_format($progressPercentage, 1) }}% payé</div>
        </div>

        {{-- Informations complémentaires --}}
        <div class="info-section">
            <div class="info-grid">
                {{-- Informations client --}}
                <div class="info-card">
                    <div class="info-title">
                        <span>👤</span>
                        Informations Client
                    </div>
                    @if($payment->pilgrim->email)
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $payment->pilgrim->email }}</span>
                    </div>
                    @endif
                    @if($payment->pilgrim->phone)
                    <div class="info-item">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value">{{ $payment->pilgrim->phone }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Catégorie:</span>
                        <span class="info-value">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID Client:</span>
                        <span class="info-value">#{{ str_pad($payment->pilgrim->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                {{-- Détails du paiement --}}
                <div class="info-card">
                    <div class="info-title">
                        <span>💳</span>
                        Détails Paiement
                    </div>
                    <div class="info-item">
                        <span class="info-label">N° Reçu:</span>
                        <span class="info-value">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Mode de paiement:</span>
                        <span class="info-value">
                            @switch($payment->payment_method)
                                @case('cash') 💵 Espèces @break
                                @case('check') 📋 Chèque @break
                                @case('bank_transfer') 🏦 Virement @break
                                @case('card') 💳 Carte @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </span>
                    </div>
                    @if($payment->reference)
                    <div class="info-item">
                        <span class="info-label">Référence:</span>
                        <span class="info-value">{{ $payment->reference }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Statut:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $payment->status }}">
                                @switch($payment->status)
                                    @case('completed') ✅ Validé @break
                                    @case('cancelled') ❌ Annulé @break
                                    @default ⏳ En attente
                                @endswitch
                            </span>
                        </span>
                    </div>
                </div>

                {{-- Informations agence --}}
                <div class="info-card">
                    <div class="info-title">
                        <span>🏢</span>
                        Informations Agence
                    </div>
                    <div class="info-item">
                        <span class="info-label">Agence:</span>
                        <span class="info-value">{{ $agencySettings['company_name'] ?? 'SAFIR' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Agent:</span>
                        <span class="info-value">{{ $servingUser->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value">{{ $agencySettings['company_phone'] ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date/Heure:</span>
                        <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Informations campagne si disponible --}}
                @if($payment->pilgrim->campaign)
                <div class="info-card">
                    <div class="info-title">
                        <span>🕌</span>
                        Campagne {{ ucfirst($payment->pilgrim->campaign->type) }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nom:</span>
                        <span class="info-value">{{ $payment->pilgrim->campaign->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Départ:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Retour:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durée:</span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->diffInDays(\Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)) }} jours
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="summary-footer">
            <div class="footer-company">{{ $agencySettings['company_name'] ?? 'SAFIR' }}</div>
            <div>
                Récapitulatif généré le {{ now()->format('d/m/Y à H:i') }}
                @if($agencySettings['company_email'])
                 | 📧 {{ $agencySettings['company_email'] }}
                @endif
            </div>
        </div>
    </div>
</body>
</html>