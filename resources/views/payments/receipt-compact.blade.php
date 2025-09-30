<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu - {{ $payment->receipt_number }}</title>
    <style>
        /* ============================================
           RECEIPT COMPACT - ONE PAGE OPTIMIZED
           Designed to fit perfectly on A4 (210mm x 297mm)
           ============================================ */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #1a1a1a;
            background: white;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 10mm;
            position: relative;
        }

        /* Container for one page */
        .receipt-wrapper {
            width: 100%;
            height: 277mm; /* 297mm - 20mm padding */
            display: flex;
            flex-direction: column;
        }

        /* Header - 60mm */
        .receipt-header {
            height: 55mm;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 6mm 6mm 0 0;
            padding: 5mm;
            color: white;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 4mm;
            position: relative;
            overflow: hidden;
        }

        .receipt-header::after {
            content: '';
            position: absolute;
            top: -15mm;
            right: -15mm;
            width: 50mm;
            height: 50mm;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .company-section {
            position: relative;
            z-index: 1;
        }

        .company-logo {
            height: 15mm;
            width: auto;
            margin-bottom: 2mm;
            background: white;
            padding: 1mm;
            border-radius: 2mm;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 1mm;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .company-info {
            font-size: 8px;
            line-height: 1.4;
            opacity: 0.95;
        }

        .company-info div {
            margin-bottom: 0.5mm;
        }

        .receipt-badge {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 4mm;
            padding: 4mm;
            text-align: center;
            position: relative;
            z-index: 1;
            height: fit-content;
        }

        .receipt-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2mm;
        }

        .receipt-number {
            font-size: 18px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            background: rgba(255,255,255,0.2);
            padding: 2mm 3mm;
            border-radius: 2mm;
            margin-bottom: 2mm;
        }

        .receipt-date {
            font-size: 8px;
            opacity: 0.9;
        }

        /* Main Content - 175mm */
        .receipt-body {
            flex: 1;
            padding: 4mm 0;
            display: flex;
            flex-direction: column;
            gap: 3mm;
        }

        /* Two-column layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4mm;
            margin-bottom: 3mm;
        }

        /* Info box */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #3b82f6;
            border-radius: 2mm;
            padding: 3mm;
        }

        .info-box-title {
            font-size: 9px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2mm;
            padding-bottom: 1mm;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1.5mm 0;
            font-size: 9px;
            border-bottom: 1px dotted #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
        }

        .info-value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
        }

        /* Payment highlight box */
        .payment-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 3mm;
            padding: 4mm;
            text-align: center;
            margin: 3mm 0;
            box-shadow: 0 2mm 6mm rgba(16, 185, 129, 0.3);
        }

        .payment-amount {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 2mm;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .payment-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.95;
            margin-bottom: 2mm;
        }

        .financial-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2mm;
            margin-top: 3mm;
        }

        .financial-item {
            background: rgba(255,255,255,0.2);
            border-radius: 2mm;
            padding: 2mm;
            text-align: center;
        }

        .financial-value {
            font-size: 12px;
            font-weight: bold;
        }

        .financial-label {
            font-size: 7px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-top: 1mm;
        }

        /* History table - compact */
        .history-section {
            margin: 2mm 0;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 2mm;
            padding-left: 2mm;
            border-left: 3px solid #3b82f6;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            border-radius: 2mm;
            overflow: hidden;
            box-shadow: 0 1mm 3mm rgba(0,0,0,0.1);
        }

        .history-table thead {
            background: linear-gradient(135deg, #334155, #475569);
            color: white;
        }

        .history-table th {
            padding: 2mm 1mm;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
            letter-spacing: 0.3px;
        }

        .history-table td {
            padding: 1.5mm 1mm;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .history-table tbody tr {
            background: white;
        }

        .history-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .history-table tbody tr.current-payment {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            font-weight: bold;
        }

        /* Status badges - compact */
        .status-badge {
            display: inline-block;
            padding: 1mm 2mm;
            border-radius: 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        /* Signatures - 40mm */
        .signatures-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4mm;
            margin: 3mm 0;
            padding: 3mm;
            background: #f8fafc;
            border-radius: 2mm;
            border: 1px dashed #cbd5e1;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 15mm;
            border: 1px dashed #94a3b8;
            border-radius: 2mm;
            margin-bottom: 2mm;
            background: white;
        }

        .signature-label {
            font-size: 8px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }

        .signature-name {
            font-size: 7px;
            color: #64748b;
            margin-top: 1mm;
        }

        /* Footer - 20mm */
        .receipt-footer {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: white;
            padding: 3mm;
            text-align: center;
            border-radius: 0 0 6mm 6mm;
            margin-top: auto;
        }

        .footer-brand {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .footer-info {
            font-size: 7px;
            line-height: 1.5;
            opacity: 0.9;
        }

        .footer-meta {
            font-size: 6px;
            opacity: 0.7;
            margin-top: 1mm;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.03;
            z-index: 0;
            font-size: 60px;
            font-weight: bold;
            color: #1e40af;
            pointer-events: none;
        }

        /* Print optimization */
        @media print {
            body {
                width: 210mm;
                height: 297mm;
                padding: 10mm;
            }

            .receipt-wrapper {
                page-break-inside: avoid;
            }

            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $currency = $agencySettings['currency_symbol'] ?? $agencySettings['default_currency'] ?? 'FCFA';
    @endphp

    <!-- Watermark -->
    <div class="watermark">REÇU OFFICIEL</div>

    <div class="receipt-wrapper">
        <!-- Header -->
        <div class="receipt-header">
            <div class="company-section">
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
                <div class="company-name">{{ $agencySettings['company_name'] ?? 'HAJJ MANAGEMENT' }}</div>
                <div class="company-info">
                    @if($agencySettings['company_address'])<div>📍 {{ $agencySettings['company_address'] }}, {{ $agencySettings['company_city'] ?? '' }}</div>@endif
                    @if($agencySettings['company_phone'])<div>📞 {{ $agencySettings['company_phone'] }} @if($agencySettings['company_phone2'])/ {{ $agencySettings['company_phone2'] }}@endif</div>@endif
                    @if($agencySettings['company_email'])<div>✉️ {{ $agencySettings['company_email'] }}</div>@endif
                    @if($agencySettings['company_registration'])<div>📋 N° Enreg: {{ $agencySettings['company_registration'] }}</div>@endif
                </div>
            </div>
            <div class="receipt-badge">
                <div class="receipt-title">Reçu de Paiement</div>
                <div class="receipt-number">{{ $payment->receipt_number ?? str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">{{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Two columns info -->
            <div class="content-grid">
                <!-- Client info -->
                <div class="info-box">
                    <div class="info-box-title">👤 Client & Pèlerin</div>
                    <div class="info-row">
                        <span class="info-label">Nom</span>
                        <span class="info-value">{{ $payment->pilgrim->full_name }}</span>
                    </div>
                    @if($payment->pilgrim->phone)
                    <div class="info-row">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value">{{ $payment->pilgrim->phone }}</span>
                    </div>
                    @endif
                    @if($payment->pilgrim->email)
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $payment->pilgrim->email }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Catégorie</span>
                        <span class="info-value">{{ strtoupper($payment->pilgrim->category ?? 'CLASSIC') }}</span>
                    </div>
                </div>

                <!-- Payment details -->
                <div class="info-box">
                    <div class="info-box-title">💳 Détails Paiement</div>
                    <div class="info-row">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mode</span>
                        <span class="info-value">
                            @switch($payment->payment_method)
                                @case('cash') Espèces @break
                                @case('check') Chèque @break
                                @case('bank_transfer') Virement @break
                                @case('card') Carte @break
                                @default {{ $payment->payment_method }}
                            @endswitch
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
                                    @default ⏳ Attente
                                @endswitch
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Campaign info (full width) -->
            @if($payment->pilgrim->campaign)
            <div class="info-box">
                <div class="info-box-title">🕌 Campagne</div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2mm;">
                    <div class="info-row" style="flex-direction: column; align-items: flex-start; border: none;">
                        <span class="info-label">Nom</span>
                        <span class="info-value">{{ $payment->pilgrim->campaign->name }}</span>
                    </div>
                    <div class="info-row" style="flex-direction: column; align-items: flex-start; border: none;">
                        <span class="info-label">Type</span>
                        <span class="info-value">{{ strtoupper($payment->pilgrim->campaign->type) }}</span>
                    </div>
                    <div class="info-row" style="flex-direction: column; align-items: flex-start; border: none;">
                        <span class="info-label">Départ</span>
                        <span class="info-value">{{ $payment->pilgrim->campaign->departure_date ? \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-row" style="flex-direction: column; align-items: flex-start; border: none;">
                        <span class="info-label">Retour</span>
                        <span class="info-value">{{ $payment->pilgrim->campaign->return_date ? \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment amount -->
            <div class="payment-box">
                <div class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $currency }}</div>
                <div class="payment-label">Montant Encaissé</div>
                <div class="financial-grid">
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Total Dû</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Payé</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-value">{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</div>
                        <div class="financial-label">Restant</div>
                    </div>
                </div>
            </div>

            <!-- History table -->
            <div class="history-section">
                <div class="section-title">Historique Paiements</div>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments->take(6) as $index => $paymentItem)
                        <tr class="{{ $paymentItem->id == $payment->id ? 'current-payment' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($paymentItem->payment_date)->format('d/m/y') }}</td>
                            <td><strong>{{ number_format($paymentItem->amount, 0, ',', ' ') }}</strong></td>
                            <td>
                                @switch($paymentItem->payment_method)
                                    @case('cash') ESP @break
                                    @case('check') CHQ @break
                                    @case('bank_transfer') VIR @break
                                    @case('card') CB @break
                                @endswitch
                            </td>
                            <td>
                                <span class="status-badge status-{{ $paymentItem->status }}">
                                    @switch($paymentItem->status)
                                        @case('completed') ✓ @break
                                        @case('cancelled') ✕ @break
                                        @default ⏳
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Signatures -->
            <div class="signatures-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">✍️ Signature Client</div>
                    <div class="signature-name">{{ $payment->pilgrim->full_name }}</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">🏢 Cachet Agence</div>
                    <div class="signature-name">Servi par: {{ $servingUser->name }}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="footer-brand">{{ $agencySettings['company_name'] ?? 'HAJJ MANAGEMENT' }}</div>
            <div class="footer-info">
                Ce reçu certifie le paiement reçu et fait foi en cas de litige • Merci de conserver ce document
            </div>
            <div class="footer-meta">
                Document généré le {{ now()->format('d/m/Y à H:i') }} • Pour réclamation, présenter ce reçu original
            </div>
        </div>
    </div>
</body>
</html>