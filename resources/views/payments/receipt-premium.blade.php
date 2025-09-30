<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu Premium - {{ $payment->receipt_number }}</title>
    <style>
        /* ============================================
           HAJJ RECEIPT - PREMIUM DESIGN
           Modern, Elegant & Professional
           ============================================ */

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--dark);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .receipt-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
        }

        /* Gradient Header */
        .receipt-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .receipt-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 30px;
            align-items: start;
        }

        .company-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .company-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13px;
            opacity: 0.95;
        }

        .company-detail {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .receipt-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            padding: 20px 30px;
            border-radius: 15px;
            text-align: center;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .receipt-number {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
        }

        /* Main Content */
        .receipt-body {
            padding: 40px;
        }

        /* Info Cards Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border: 2px solid var(--border);
            border-radius: 15px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.2);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            text-align: right;
        }

        /* Payment Amount Highlight */
        .payment-highlight {
            background: linear-gradient(135deg, var(--secondary) 0%, #059669 100%);
            color: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3);
        }

        .payment-highlight::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .payment-amount {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .payment-label {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        /* Financial Summary */
        .financial-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .financial-item {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .financial-value {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .financial-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        /* Payment History Table */
        .history-section {
            margin: 30px 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 30px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .history-table thead {
            background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%);
        }

        .history-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--dark);
        }

        .history-table td {
            padding: 15px;
            border-top: 1px solid var(--border);
            font-size: 13px;
        }

        .history-table tbody tr {
            background: white;
            transition: background 0.2s ease;
        }

        .history-table tbody tr:hover {
            background: #f9fafb;
        }

        .history-table tbody tr.current-payment {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            font-weight: 600;
        }

        .history-table tbody tr.current-payment td {
            border-top-color: var(--accent);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: #d1fae5;
            color: var(--success);
        }

        .status-pending {
            background: #fed7aa;
            color: var(--warning);
        }

        .status-cancelled {
            background: #fee2e2;
            color: var(--danger);
        }

        /* Payment Method Badge */
        .method-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: #e0e7ff;
            color: #4338ca;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Signatures Section */
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin: 40px 0;
            padding: 30px;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border-radius: 15px;
            border: 2px dashed var(--border);
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 100%;
            height: 80px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            margin-bottom: 15px;
            background: white;
        }

        .signature-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Footer */
        .receipt-footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 800px;
            margin: 0 auto;
        }

        .footer-brand {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .footer-info {
            font-size: 12px;
            opacity: 0.8;
            line-height: 1.8;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 12px;
            opacity: 0.8;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }

            .info-card:hover {
                transform: none;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .financial-summary {
                grid-template-columns: 1fr;
            }

            .signatures {
                grid-template-columns: 1fr;
            }

            .receipt-body {
                padding: 20px;
            }

            .payment-amount {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    @php
        $currency = $agencySettings['currency_symbol'] ?? $agencySettings['default_currency'] ?? 'FCFA';
    @endphp

    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="header-content">
                <div class="company-info">
                    <h1>{{ $agencySettings['company_name'] ?? 'HAJJ MANAGEMENT' }}</h1>
                    <div class="company-tagline">{{ $agencySettings['company_slogan'] ?? 'Votre voyage spirituel en toute sérénité' }}</div>
                    <div class="company-details">
                        @if($agencySettings['company_phone'])
                        <div class="company-detail">
                            <span>📞</span>
                            <span>{{ $agencySettings['company_phone'] }}</span>
                        </div>
                        @endif
                        @if($agencySettings['company_email'])
                        <div class="company-detail">
                            <span>✉️</span>
                            <span>{{ $agencySettings['company_email'] }}</span>
                        </div>
                        @endif
                        @if($agencySettings['company_address'])
                        <div class="company-detail">
                            <span>📍</span>
                            <span>{{ $agencySettings['company_address'] }}, {{ $agencySettings['company_city'] ?? '' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="receipt-badge">
                    <div class="receipt-title">Reçu de Paiement</div>
                    <div class="receipt-number">{{ $payment->receipt_number ?? str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div style="margin-top: 10px; font-size: 12px; opacity: 0.9;">
                        {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Info Cards -->
            <div class="info-grid">
                <!-- Client Info -->
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                        👤
                    </div>
                    <div class="card-title">Informations Client</div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="info-label">Nom complet</span>
                            <span class="info-value">{{ $payment->pilgrim->full_name }}</span>
                        </div>
                        @if($payment->pilgrim->email)
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $payment->pilgrim->email }}</span>
                        </div>
                        @endif
                        @if($payment->pilgrim->phone)
                        <div class="info-row">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value">{{ $payment->pilgrim->phone }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Catégorie</span>
                            <span class="info-value">{{ strtoupper($payment->pilgrim->category ?? 'CLASSIC') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Campaign Info -->
                @if($payment->pilgrim->campaign)
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                        🕌
                    </div>
                    <div class="card-title">Campagne</div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="info-label">Nom</span>
                            <span class="info-value">{{ $payment->pilgrim->campaign->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value">{{ strtoupper($payment->pilgrim->campaign->type) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Départ</span>
                            <span class="info-value">{{ $payment->pilgrim->campaign->departure_date ? \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Retour</span>
                            <span class="info-value">{{ $payment->pilgrim->campaign->return_date ? \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Details -->
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                        💳
                    </div>
                    <div class="card-title">Détails Paiement</div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="info-label">Date</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mode</span>
                            <span class="info-value">
                                <span class="method-badge">
                                    @switch($payment->payment_method)
                                        @case('cash') 💵 Espèces @break
                                        @case('check') 📋 Chèque @break
                                        @case('bank_transfer') 🏦 Virement @break
                                        @case('card') 💳 Carte @break
                                        @default {{ $payment->payment_method }}
                                    @endswitch
                                </span>
                            </span>
                        </div>
                        @if($payment->reference)
                        <div class="info-row">
                            <span class="info-label">Référence</span>
                            <span class="info-value">{{ $payment->reference }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Statut</span>
                            <span class="info-value">
                                <span class="status-badge status-{{ $payment->status }}">
                                    @switch($payment->status)
                                        @case('completed') ✓ Validé @break
                                        @case('cancelled') ✕ Annulé @break
                                        @default ⏳ En attente
                                    @endswitch
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Amount Highlight -->
            <div class="payment-highlight">
                <div class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $currency }}</div>
                <div class="payment-label">Montant Encaissé</div>

                <div class="financial-summary">
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Total Dû</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Total Payé</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Reste à Payer</div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="history-section">
                <div class="section-title">
                    Historique des Paiements
                </div>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Référence</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments as $index => $paymentItem)
                        <tr class="{{ $paymentItem->id == $payment->id ? 'current-payment' : '' }}">
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($paymentItem->payment_date)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($paymentItem->amount, 0, ',', ' ') }} {{ $currency }}</strong></td>
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
                                        @case('completed') ✓ Validé @break
                                        @case('cancelled') ✕ Annulé @break
                                        @default ⏳ En attente
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">✍️ Signature Client</div>
                    <div style="margin-top: 5px; font-size: 11px; color: #6b7280;">
                        {{ $payment->pilgrim->full_name }}
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">🏢 Cachet & Signature Agence</div>
                    <div style="margin-top: 5px; font-size: 11px; color: #6b7280;">
                        Servi par: {{ $servingUser->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="footer-content">
                <div class="footer-brand">{{ $agencySettings['company_name'] ?? 'HAJJ MANAGEMENT' }}</div>
                <div class="footer-info">
                    Ce reçu certifie le paiement reçu et fait foi de preuve en cas de litige.<br>
                    Pour toute réclamation, veuillez présenter ce document original.<br>
                    @if($agencySettings['company_registration'])
                    N° d'enregistrement: {{ $agencySettings['company_registration'] }}<br>
                    @endif
                    Document généré le {{ now()->format('d/m/Y à H:i') }}
                </div>
                <div class="footer-links">
                    @if($agencySettings['company_phone'])
                    <span>📞 {{ $agencySettings['company_phone'] }}</span>
                    @endif
                    @if($agencySettings['company_email'])
                    <span>✉️ {{ $agencySettings['company_email'] }}</span>
                    @endif
                    @if($agencySettings['company_website'])
                    <span>🌐 {{ $agencySettings['company_website'] }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>