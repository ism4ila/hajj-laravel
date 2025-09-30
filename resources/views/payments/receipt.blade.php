<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
            font-size: 10px;
            line-height: 1.4;
        }
        .receipt-container {
            width: 100%;
            height: 100%;
            padding: 20px;
            background-color: #ffffff;
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }
        .company-logo {
            max-width: 150px;
            max-height: 70px;
            margin-bottom: 10px;
        }
        .company-info .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .company-info .company-details {
            font-size: 10px;
            color: #6c757d;
        }
        .receipt-details .receipt-title {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            color: #343a40;
            margin-bottom: 5px;
        }
        .receipt-details .receipt-meta {
            text-align: right;
            font-size: 10px;
            color: #6c757d;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        .info-section h2 {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 6px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            width: 100px;
        }
        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        .summary-grid {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .summary-item .summary-label {
            font-size: 12px;
            color: #6c757d;
        }
        .summary-item .summary-value {
            font-size: 16px;
            font-weight: bold;
        }
        .summary-item .summary-value.total { color: #007bff; }
        .summary-item .summary-value.paid { color: #28a745; }
        .summary-item .summary-value.remaining { color: #dc3545; }

        .payment-history-section h2 {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 6px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th, .history-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .history-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .current-payment td {
            background-color: #e7f3ff;
            font-weight: bold;
        }
        .receipt-footer {
            position: absolute;
            bottom: 25px;
            left: 25px;
            right: 25px;
            padding-top: 15px;
            border-top: 2px solid #007bff;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    @php
        $currency = $agencySettings["currency_symbol"] ?? "FCFA";
        $logoSrc = null;
        if ($agencySettings["company_logo"] ?? null) {
            $logoPath = storage_path("app/public/logos/" . $agencySettings["company_logo"]);
            if (file_exists($logoPath)) {
                $logoContent = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoContent);
                $logoExtension = pathinfo($logoPath, PATHINFO_EXTENSION);
                $logoSrc = "data:image/" . $logoExtension . ";base64," . $logoBase64;
            }
        }
    @endphp
    <div class="receipt-container">
        <header class="receipt-header">
            <div class="company-info">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="Logo" class="company-logo">
                @endif
                <div class="company-name">{{ $agencySettings["company_name"] ?? "SAFIR VOYAGES" }}</div>
                <div class="company-details">
                    {{ $agencySettings["company_address"] ?? "" }}<br>
                    Tél: {{ $agencySettings["company_phone"] ?? "N/A" }} | Email: {{ $agencySettings["company_email"] ?? "N/A" }}
                </div>
            </div>
            <div class="receipt-details">
                <div class="receipt-title">REÇU</div>
                <div class="receipt-meta">
                    <strong>N° de reçu:</strong> {{ str_pad($payment->id, 6, "0", STR_PAD_LEFT) }}<br>
                    <strong>Date:</strong> {{ now()->format("d/m/Y") }}
                </div>
            </div>
        </header>

        <main>
            <div class="info-grid">
                <section class="info-section">
                    <h2>Facturé à</h2>
                    <div class="info-row"><div class="info-label">Pèlerin:</div><div><strong>{{ $payment->pilgrim->full_name }}</strong></div></div>
                    <div class="info-row"><div class="info-label">Client:</div><div>{{ $payment->pilgrim->client->full_name ?? "N/A" }}</div></div>
                    <div class="info-row"><div class="info-label">Téléphone:</div><div>{{ $payment->pilgrim->phone ?? "N/A" }}</div></div>
                </section>
                <section class="info-section">
                    <h2>Détails du Paiement</h2>
                    <div class="info-row"><div class="info-label">Montant Payé:</div><div><strong>{{ number_format($payment->amount, 0, ",", " ") }} {{ $currency }}</strong></div></div>
                    <div class="info-row"><div class="info-label">Date paiement:</div><div>{{ \Carbon\Carbon::parse($payment->payment_date)->format("d/m/Y") }}</div></div>
                    <div class="info-row"><div class="info-label">Méthode:</div><div>{{ ucfirst($payment->payment_method) }}</div></div>
                </section>
            </div>

            <div class="summary-section">
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">Montant Total</div>
                        <div class="summary-value total">{{ number_format($payment->pilgrim->total_amount, 0, ",", " ") }} {{ $currency }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Payé</div>
                        <div class="summary-value paid">{{ number_format($payment->pilgrim->paid_amount, 0, ",", " ") }} {{ $currency }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Solde Restant</div>
                        <div class="summary-value remaining">{{ number_format($payment->pilgrim->remaining_amount, 0, ",", " ") }} {{ $currency }}</div>
                    </div>
                </div>
            </div>

            <div class="payment-history-section">
                <h2>Historique des Paiements</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Méthode</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPayments->take(8) as $p)
                        <tr class="{{ $p->id == $payment->id ? 'current-payment' : '' }}">
                            <td>{{ \Carbon\Carbon::parse($p->payment_date)->format("d/m/Y") }}</td>
                            <td>{{ number_format($p->amount, 0, ",", " ") }} {{ $currency }}</td>
                            <td>{{ ucfirst($p->payment_method) }}</td>
                            <td>{{ $p->reference ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="receipt-footer">
            Merci pour votre paiement. | {{ $agencySettings["company_name"] ?? "SAFIR VOYAGES" }}
        </footer>
    </div>
</body>
</html>