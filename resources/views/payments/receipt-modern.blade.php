<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $payment->pilgrim->client ? $payment->pilgrim->client->full_name : $payment->pilgrim->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, DejaVu Sans, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #2d3748;
            background: #ffffff;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            position: relative;
            min-height: 100vh;
        }

        /* Header avec gradient moderne */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.1) 0px,
                rgba(255,255,255,0.1) 2px,
                transparent 2px,
                transparent 20px
            );
            opacity: 0.3;
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            padding: 8px;
        }

        .company-info h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .company-details {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .receipt-info {
            text-align: right;
            font-size: 16px;
        }

        .receipt-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .receipt-number {
            font-size: 18px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .receipt-date {
            opacity: 0.9;
        }

        /* Section principale avec design moderne */
        .main-content {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 0;
        }

        /* Montant en gros - Section la plus importante */
        .amount-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .amount-section::before {
            content: '💰';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 60px;
            opacity: 0.2;
        }

        .amount-label {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .amount-value {
            font-size: 56px;
            font-weight: 900;
            margin-bottom: 10px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-family: 'Segoe UI', monospace;
        }

        .amount-currency {
            font-size: 24px;
            font-weight: 600;
            opacity: 0.9;
        }

        /* Informations du client - Section importante */
        .client-section {
            background: white;
            padding: 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .client-name {
            font-size: 32px;
            font-weight: 900;
            color: #1a202c;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .client-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f7fafc;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail-icon {
            font-size: 18px;
            color: #667eea;
            width: 24px;
            text-align: center;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        /* Détails du paiement */
        .payment-details {
            background: white;
            padding: 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .payment-item {
            background: #f7fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .payment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .payment-item-label {
            font-size: 14px;
            font-weight: 600;
            color: #718096;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payment-item-value {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
        }

        /* Statut avec badge moderne */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }

        /* Signatures */
        .signatures {
            background: white;
            padding: 40px 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 60px;
            border-bottom: 2px solid #4a5568;
            margin-bottom: 15px;
            position: relative;
        }

        .signature-label {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer moderne */
        .footer {
            background: #2d3748;
            color: white;
            padding: 25px 30px;
            text-align: center;
            border-radius: 0 0 15px 15px;
        }

        .footer-content {
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .footer-highlight {
            font-weight: 600;
            color: #90cdf4;
        }

        /* Watermark subtil */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            opacity: 0.03;
            z-index: 0;
            font-size: 120px;
            font-weight: 900;
            color: #667eea;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .amount-value {
                font-size: 40px;
            }

            .client-name {
                font-size: 24px;
            }

            .signatures {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        /* Impression */
        @media print {
            body {
                padding: 0;
            }

            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">PAYÉ</div>

    <div class="container">
        <!-- Header moderne avec gradient -->
        <div class="header">
            <div class="header-content">
                <div class="logo-section">
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
                    <div class="company-info">
                        <h1>{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</h1>
                        <div class="company-details">
                            @if($agencySettings['company_address'] ?? null)
                                📍 {{ $agencySettings['company_address'] }}<br>
                            @endif
                            @if($agencySettings['company_phone'] ?? null)
                                📞 {{ $agencySettings['company_phone'] }}
                            @endif
                            @if($agencySettings['company_email'] ?? null)
                                ✉️ {{ $agencySettings['company_email'] }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="receipt-info">
                    <div class="receipt-title">REÇU DE PAIEMENT</div>
                    <div class="receipt-number">N° {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="receipt-date">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <!-- Section montant - LA PLUS IMPORTANTE -->
            <div class="amount-section">
                <div class="amount-label">Montant Payé</div>
                <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }}</div>
                <div class="amount-currency">{{ $agencySettings['default_currency'] ?? 'FCFA' }}</div>
            </div>

            <!-- Informations client - NOM EN GRAS -->
            <div class="client-section">
                <div class="section-title">👤 Informations Client</div>

                @if($payment->pilgrim->client)
                    <div class="client-name">{{ $payment->pilgrim->client->full_name }}</div>
                    <div style="font-size: 18px; color: #718096; margin-bottom: 10px;">
                        <strong>Pèlerin:</strong> {{ $payment->pilgrim->full_name }}
                    </div>
                @else
                    <div class="client-name">{{ $payment->pilgrim->full_name }}</div>
                    <div style="font-size: 16px; color: #e53e3e; font-weight: 600;">⚠️ Client non associé</div>
                @endif

                <div class="client-details">
                    @if(($payment->pilgrim->client && $payment->pilgrim->client->phone) || $payment->pilgrim->phone)
                    <div class="detail-item">
                        <div class="detail-icon">📞</div>
                        <div class="detail-content">
                            <div class="detail-label">Téléphone</div>
                            <div class="detail-value">{{ $payment->pilgrim->client->phone ?? $payment->pilgrim->phone }}</div>
                        </div>
                    </div>
                    @endif

                    @if(($payment->pilgrim->client && $payment->pilgrim->client->email) || $payment->pilgrim->email)
                    <div class="detail-item">
                        <div class="detail-icon">✉️</div>
                        <div class="detail-content">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $payment->pilgrim->client->email ?? $payment->pilgrim->email }}</div>
                        </div>
                    </div>
                    @endif

                    @if($payment->pilgrim->campaign)
                    <div class="detail-item">
                        <div class="detail-icon">🕋</div>
                        <div class="detail-content">
                            <div class="detail-label">Campagne</div>
                            <div class="detail-value">{{ $payment->pilgrim->campaign->name }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="detail-item">
                        <div class="detail-icon">👥</div>
                        <div class="detail-content">
                            <div class="detail-label">Catégorie</div>
                            <div class="detail-value">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du paiement -->
            <div class="payment-details">
                <div class="section-title">💳 Détails du Paiement</div>

                <div class="payment-grid">
                    <div class="payment-item">
                        <div class="payment-item-label">Mode de Paiement</div>
                        <div class="payment-item-value">
                            @switch($payment->payment_method)
                                @case('cash') 💵 Espèces @break
                                @case('check') 📋 Chèque @break
                                @case('bank_transfer') 🏦 Virement @break
                                @case('card') 💳 Carte @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </div>
                    </div>

                    <div class="payment-item">
                        <div class="payment-item-label">Date</div>
                        <div class="payment-item-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                    </div>

                    @if($payment->reference)
                    <div class="payment-item">
                        <div class="payment-item-label">Référence</div>
                        <div class="payment-item-value">{{ $payment->reference }}</div>
                    </div>
                    @endif

                    <div class="payment-item">
                        <div class="payment-item-label">Statut</div>
                        <div class="payment-item-value">
                            <span class="status-badge status-{{ $payment->status }}">
                                @switch($payment->status)
                                    @case('completed') ✅ Terminé @break
                                    @case('cancelled') ❌ Annulé @break
                                    @default ⏳ En Attente
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                @if($payment->notes)
                <div style="margin-top: 20px; padding: 15px; background: #fff7ed; border-left: 4px solid #f59e0b; border-radius: 6px;">
                    <div style="font-weight: 600; color: #92400e; margin-bottom: 5px;">📝 Notes:</div>
                    <div style="color: #78350f;">{{ $payment->notes }}</div>
                </div>
                @endif
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Signature du Client</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Cachet de l'Agence</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div><span class="footer-highlight">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</span> - Système de Gestion</div>
                <div>Reçu généré le {{ now()->format('d/m/Y à H:i') }} par {{ $servingUser->name ?? 'Système' }}</div>
                <div>📄 Conservez ce reçu pour vos dossiers</div>
                @if($agencySettings['company_phone'] ?? null)
                <div>📞 Support: {{ $agencySettings['company_phone'] }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>