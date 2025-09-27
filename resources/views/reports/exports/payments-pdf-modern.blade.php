<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Paiements</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #2d3748;
            background: #ffffff;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* Header moderne avec gradient */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
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
                transparent 15px
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
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            padding: 5px;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .company-details {
            font-size: 12px;
            opacity: 0.9;
            line-height: 1.3;
        }

        .report-info {
            text-align: right;
            font-size: 14px;
        }

        .report-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .report-date {
            opacity: 0.9;
        }

        /* Section des statistiques */
        .stats-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stats-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
            display: inline-block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            text-align: center;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tableau des paiements */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .payments-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            padding: 12px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payments-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
            vertical-align: top;
        }

        .payments-table tr:nth-child(even) {
            background: #f7fafc;
        }

        .payments-table tr:hover {
            background: #edf2f7;
        }

        /* Nom du pèlerin en gras */
        .pilgrim-name {
            font-weight: 900;
            font-size: 10px;
            color: #1a202c;
            margin-bottom: 3px;
        }

        .client-name {
            font-size: 9px;
            color: #4a5568;
            font-style: italic;
        }

        .no-client {
            font-size: 8px;
            color: #e53e3e;
            font-weight: 600;
        }

        /* Montant très visible */
        .amount-cell {
            font-weight: 900;
            font-size: 11px;
            color: #065f46;
            text-align: right;
            background: #d1fae5;
            border-radius: 4px;
            padding: 8px !important;
        }

        /* Badges de statut */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Mode de paiement avec icônes */
        .payment-method {
            font-size: 9px;
            font-weight: 600;
            color: #4a5568;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }

        .footer-highlight {
            font-weight: 600;
            color: #4a5568;
        }

        /* Totaux */
        .totals-row {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            font-weight: 700 !important;
        }

        .totals-row td {
            border-bottom: none !important;
            font-size: 11px !important;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            opacity: 0.03;
            z-index: 0;
            font-size: 100px;
            font-weight: 900;
            color: #667eea;
            pointer-events: none;
        }

        /* Responsive pour impression */
        @media print {
            body {
                padding: 0;
                font-size: 10px;
            }

            .container {
                box-shadow: none;
            }

            .payments-table th {
                font-size: 9px;
            }

            .payments-table td {
                font-size: 8px;
                padding: 6px 4px;
            }

            .amount-cell {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">PAYÉ</div>

    <div class="container">
        <!-- Header moderne -->
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
                <div class="report-info">
                    <div class="report-title">📊 RAPPORT DES PAIEMENTS</div>
                    <div class="report-date">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
                    @if(isset($params['from_date']) || isset($params['to_date']))
                        <div style="margin-top: 8px; font-size: 12px;">
                            @if(isset($params['from_date']) && $params['from_date'])
                                Du {{ \Carbon\Carbon::parse($params['from_date'])->format('d/m/Y') }}
                            @endif
                            @if(isset($params['to_date']) && $params['to_date'])
                                au {{ \Carbon\Carbon::parse($params['to_date'])->format('d/m/Y') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-section">
            <div class="stats-title">📈 Résumé Financier</div>
            <div class="stats-grid">
                @php
                    $totalAmount = $payments->where('status', 'completed')->sum('amount');
                    $pendingAmount = $payments->where('status', 'pending')->sum('amount');
                    $cancelledAmount = $payments->where('status', 'cancelled')->sum('amount');
                    $totalCount = $payments->where('status', 'completed')->count();
                @endphp

                <div class="stat-item">
                    <div class="stat-value">{{ number_format($totalAmount, 0, ',', ' ') }}</div>
                    <div class="stat-label">💰 Total Encaissé ({{ $agencySettings['default_currency'] ?? 'FCFA' }})</div>
                </div>

                <div class="stat-item">
                    <div class="stat-value">{{ $totalCount }}</div>
                    <div class="stat-label">✅ Paiements Réussis</div>
                </div>

                <div class="stat-item">
                    <div class="stat-value">{{ number_format($pendingAmount, 0, ',', ' ') }}</div>
                    <div class="stat-label">⏳ En Attente ({{ $agencySettings['default_currency'] ?? 'FCFA' }})</div>
                </div>

                <div class="stat-item">
                    <div class="stat-value">{{ $totalCount > 0 ? number_format($totalAmount / $totalCount, 0, ',', ' ') : '0' }}</div>
                    <div class="stat-label">📊 Montant Moyen ({{ $agencySettings['default_currency'] ?? 'FCFA' }})</div>
                </div>
            </div>
        </div>

        <!-- Tableau des paiements -->
        <table class="payments-table">
            <thead>
                <tr>
                    <th style="width: 25%;">👤 Client / Pèlerin</th>
                    <th style="width: 15%;">📞 Contact</th>
                    <th style="width: 15%;">🕋 Campagne</th>
                    <th style="width: 12%;">💰 Montant</th>
                    <th style="width: 10%;">💳 Mode</th>
                    <th style="width: 10%;">📅 Date</th>
                    <th style="width: 8%;">📋 Référence</th>
                    <th style="width: 5%;">✓ Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>
                        @if($payment->pilgrim->client)
                            <div class="pilgrim-name">{{ $payment->pilgrim->client->full_name }}</div>
                            <div class="client-name">Pèlerin: {{ $payment->pilgrim->full_name }}</div>
                        @else
                            <div class="pilgrim-name">{{ $payment->pilgrim->full_name }}</div>
                            <div class="no-client">⚠️ Client non associé</div>
                        @endif
                    </td>
                    <td>
                        @if($payment->pilgrim->client)
                            @if($payment->pilgrim->client->phone)
                                <div>📞 {{ $payment->pilgrim->client->phone }}</div>
                            @endif
                            @if($payment->pilgrim->client->email)
                                <div style="font-size: 8px;">✉️ {{ $payment->pilgrim->client->email }}</div>
                            @endif
                        @else
                            @if($payment->pilgrim->phone)
                                <div>📞 {{ $payment->pilgrim->phone }}</div>
                            @endif
                            @if($payment->pilgrim->email)
                                <div style="font-size: 8px;">✉️ {{ $payment->pilgrim->email }}</div>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($payment->pilgrim->campaign)
                            <div style="font-weight: 600;">{{ $payment->pilgrim->campaign->name }}</div>
                            <div style="font-size: 8px; color: #718096;">{{ ucfirst($payment->pilgrim->campaign->type) }}</div>
                        @else
                            <span style="color: #a0aec0;">Aucune</span>
                        @endif
                    </td>
                    <td class="amount-cell">
                        {{ number_format($payment->amount, 0, ',', ' ') }}<br>
                        <span style="font-size: 8px; opacity: 0.8;">{{ $agencySettings['default_currency'] ?? 'FCFA' }}</span>
                    </td>
                    <td>
                        <div class="payment-method">
                            @switch($payment->payment_method)
                                @case('cash') 💵 Espèces @break
                                @case('check') 📋 Chèque @break
                                @case('bank_transfer') 🏦 Virement @break
                                @case('card') 💳 Carte @break
                                @default {{ $payment->payment_method }}
                            @endswitch
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                        <div style="font-size: 8px; color: #718096;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($payment->reference)
                            <div style="font-weight: 600; font-size: 8px;">{{ $payment->reference }}</div>
                        @else
                            <span style="color: #a0aec0;">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $payment->status }}">
                            @switch($payment->status)
                                @case('completed') ✅ @break
                                @case('cancelled') ❌ @break
                                @default ⏳
                            @endswitch
                        </span>
                    </td>
                </tr>
                @endforeach

                <!-- Ligne des totaux -->
                <tr class="totals-row">
                    <td colspan="3" style="text-align: right; font-weight: 700;">
                        🧮 TOTAUX GÉNÉRAUX:
                    </td>
                    <td style="text-align: right; font-weight: 900; font-size: 12px !important;">
                        {{ number_format($totalAmount, 0, ',', ' ') }}<br>
                        <span style="font-size: 9px;">{{ $agencySettings['default_currency'] ?? 'FCFA' }}</span>
                    </td>
                    <td colspan="2" style="text-align: center;">
                        {{ $totalCount }} paiements réussis
                    </td>
                    <td colspan="2" style="text-align: center;">
                        {{ $payments->count() }} total
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div><span class="footer-highlight">{{ $agencySettings['company_name'] ?? 'Agence Hajj & Omra' }}</span> - Système de Gestion</div>
            <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }} | {{ $payments->count() }} paiements listés</div>
            @if($agencySettings['company_phone'] ?? null)
            <div>📞 Contact: {{ $agencySettings['company_phone'] }} | ✉️ {{ $agencySettings['company_email'] ?? '' }}</div>
            @endif
            <div style="margin-top: 10px; font-style: italic;">
                📄 Document confidentiel - Usage interne uniquement
            </div>
        </div>
    </div>
</body>
</html>