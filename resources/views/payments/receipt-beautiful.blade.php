<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 30px;
        }

        .receipt-container {
            max-width: 210mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        /* En-tête élégant */
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #3498db;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px auto;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
            display: block;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .company-details {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin-top: 10px;
        }

        /* Corps du reçu */
        .receipt-body {
            padding: 40px 30px;
        }

        /* Section montant - LA STAR */
        .amount-section {
            text-align: center;
            background: #f8f9fa;
            border: 3px solid #28a745;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            position: relative;
        }

        .amount-section::before {
            content: '✓';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .amount-label {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .amount-value {
            font-size: 48px;
            font-weight: 900;
            color: #28a745;
            margin-bottom: 5px;
            font-family: 'Courier New', monospace;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .amount-currency {
            font-size: 20px;
            color: #6c757d;
            font-weight: 600;
        }

        /* Informations principales */
        .info-grid {
            display: table;
            width: 100%;
            margin: 30px 0;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 15px;
        }

        .info-column:first-child {
            border-right: 2px solid #e9ecef;
            padding-left: 0;
        }

        .info-column:last-child {
            padding-right: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: inline-block;
        }

        /* Nom du client/pèlerin - TRÈS VISIBLE */
        .client-name {
            font-size: 24px;
            font-weight: 900;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #ffc107;
            text-align: center;
        }

        .info-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 120px;
            font-size: 13px;
        }

        .info-value {
            flex: 1;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Détails du paiement */
        .payment-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }

        .payment-grid {
            display: table;
            width: 100%;
        }

        .payment-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            width: 25%;
        }

        .payment-item-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .payment-item-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .payment-item-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Statut avec style */
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }

        /* Pied de page élégant */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .footer-content {
            margin-bottom: 15px;
        }

        .footer-line {
            margin: 8px 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .footer-highlight {
            font-weight: bold;
            color: #3498db;
        }

        /* Signatures */
        .signatures {
            display: table;
            width: 100%;
            margin: 40px 0 20px 0;
        }

        .signature-block {
            display: table-cell;
            text-align: center;
            width: 50%;
            padding: 0 20px;
        }

        .signature-line {
            height: 60px;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 10px;
            position: relative;
        }

        .signature-label {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Numéro de reçu */
        .receipt-number {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 2px solid #3498db;
        }

        .receipt-number-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .receipt-number-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Note importante */
        .important-note {
            background: #e3f2fd;
            border-left: 5px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .important-note-title {
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 5px;
        }

        .important-note-text {
            color: #424242;
            font-size: 13px;
        }

        /* Responsive pour impression */
        @media print {
            body {
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Numéro de reçu -->
        <div class="receipt-number">
            <div class="receipt-number-label">Reçu N°</div>
            <div class="receipt-number-value">{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- En-tête -->
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
                    <img src="{{ $logoSrc }}" alt="Logo" class="company-logo">
                @endif
            @endif

            <div class="company-name">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</div>
            <div class="company-details">
                @if($agencySettings['company_address'] ?? null)
                    📍 {{ $agencySettings['company_address'] }}<br>
                @endif
                📞 {{ $agencySettings['company_phone'] ?? 'N/A' }} |
                ✉️ {{ $agencySettings['company_email'] ?? 'N/A' }}
            </div>
            <div class="receipt-title">REÇU DE PAIEMENT</div>
        </div>

        <!-- Corps du reçu -->
        <div class="receipt-body">
            <!-- Montant - SECTION PRINCIPALE -->
            <div class="amount-section">
                <div class="amount-label">Montant Encaissé</div>
                <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }}</div>
                <div class="amount-currency">{{ $agencySettings['default_currency'] ?? 'FCFA' }}</div>
            </div>

            <!-- Nom du client - TRÈS VISIBLE -->
            @if($payment->pilgrim->client)
                <div class="client-name">{{ $payment->pilgrim->client->full_name }}</div>
            @else
                <div class="client-name">{{ $payment->pilgrim->full_name }}</div>
            @endif

            <!-- Informations principales -->
            <div class="info-grid">
                <div class="info-column">
                    <div class="section-title">👤 Informations Client</div>

                    @if($payment->pilgrim->client)
                        <div class="info-item">
                            <div class="info-label">Client :</div>
                            <div class="info-value">{{ $payment->pilgrim->client->full_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Pèlerin :</div>
                            <div class="info-value">{{ $payment->pilgrim->full_name }}</div>
                        </div>
                        @if($payment->pilgrim->client->phone)
                        <div class="info-item">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $payment->pilgrim->client->phone }}</div>
                        </div>
                        @endif
                        @if($payment->pilgrim->client->email)
                        <div class="info-item">
                            <div class="info-label">Email :</div>
                            <div class="info-value">{{ $payment->pilgrim->client->email }}</div>
                        </div>
                        @endif
                    @else
                        <div class="info-item">
                            <div class="info-label">Nom :</div>
                            <div class="info-value">{{ $payment->pilgrim->full_name }}</div>
                        </div>
                        @if($payment->pilgrim->phone)
                        <div class="info-item">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $payment->pilgrim->phone }}</div>
                        </div>
                        @endif
                        <div style="color: #dc3545; font-weight: bold; margin-top: 10px;">
                            ⚠️ Client non associé
                        </div>
                    @endif

                    <div class="info-item">
                        <div class="info-label">Catégorie :</div>
                        <div class="info-value">{{ ucfirst($payment->pilgrim->category ?? 'Standard') }}</div>
                    </div>
                </div>

                <div class="info-column">
                    <div class="section-title">🕋 Informations Voyage</div>

                    @if($payment->pilgrim->campaign)
                        <div class="info-item">
                            <div class="info-label">Campagne :</div>
                            <div class="info-value">{{ $payment->pilgrim->campaign->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Type :</div>
                            <div class="info-value">{{ ucfirst($payment->pilgrim->campaign->type) }}</div>
                        </div>
                        @if($payment->pilgrim->campaign->departure_date)
                        <div class="info-item">
                            <div class="info-label">Départ :</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}</div>
                        </div>
                        @endif
                        @if($payment->pilgrim->campaign->return_date)
                        <div class="info-item">
                            <div class="info-label">Retour :</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
                        </div>
                        @endif
                    @else
                        <div style="color: #6c757d; font-style: italic;">
                            Aucune campagne associée
                        </div>
                    @endif
                </div>
            </div>

            <!-- Détails du paiement -->
            <div class="payment-details">
                <div class="section-title">💳 Détails du Paiement</div>
                <div class="payment-grid">
                    <div class="payment-item">
                        <div class="payment-item-icon">💵</div>
                        <div class="payment-item-label">Mode</div>
                        <div class="payment-item-value">
                            @switch($payment->payment_method)
                                @case('cash') Espèces @break
                                @case('check') Chèque @break
                                @case('bank_transfer') Virement @break
                                @case('card') Carte @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </div>
                    </div>

                    <div class="payment-item">
                        <div class="payment-item-icon">📅</div>
                        <div class="payment-item-label">Date</div>
                        <div class="payment-item-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                    </div>

                    <div class="payment-item">
                        <div class="payment-item-icon">🔖</div>
                        <div class="payment-item-label">Référence</div>
                        <div class="payment-item-value">{{ $payment->reference ?? 'N/A' }}</div>
                    </div>

                    <div class="payment-item">
                        <div class="payment-item-icon">✅</div>
                        <div class="payment-item-label">Statut</div>
                        <div class="payment-item-value">
                            <span class="status-badge status-{{ $payment->status }}">
                                @switch($payment->status)
                                    @case('completed') Terminé @break
                                    @case('cancelled') Annulé @break
                                    @default En Attente
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                @if($payment->notes)
                <div style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <strong style="color: #856404;">📝 Notes :</strong>
                    <div style="color: #6c757d; margin-top: 5px;">{{ $payment->notes }}</div>
                </div>
                @endif
            </div>

            <!-- Note importante -->
            <div class="important-note">
                <div class="important-note-title">📋 Important</div>
                <div class="important-note-text">
                    Conservez ce reçu comme preuve de paiement. En cas de réclamation, présentez ce document.
                </div>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-label">Signature du Client</div>
                </div>
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-label">Cachet de l'Agence</div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-line">
                    <span class="footer-highlight">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</span> - Système de Gestion
                </div>
                <div class="footer-line">
                    Reçu généré le {{ now()->format('d/m/Y à H:i') }} par {{ $servingUser->name ?? 'Système' }}
                </div>
                <div class="footer-line">
                    📞 {{ $agencySettings['company_phone'] ?? 'N/A' }} |
                    ✉️ {{ $agencySettings['company_email'] ?? 'N/A' }}
                </div>
                <div class="footer-line" style="margin-top: 10px; font-size: 12px; opacity: 0.8;">
                    Merci pour votre confiance 🙏
                </div>
            </div>
        </div>
    </div>
</body>
</html>