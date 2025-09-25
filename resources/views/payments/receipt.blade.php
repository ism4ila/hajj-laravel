<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement #{{ $payment->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }

        .receipt-number {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
            color: #666;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .payment-amount {
            background-color: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .payment-amount .amount {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }

        .payment-amount .currency {
            font-size: 14px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #faeeba;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 50px;
        }

        .signature-label {
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 30px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 123, 255, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    {{-- Watermark --}}
    <div class="watermark">{{ config('app.name', 'Hajj Management') }}</div>

    {{-- Header --}}
    <div class="header">
        <div class="company-name">{{ config('app.name', 'Hajj Management') }}</div>
        <div>Agence de Voyages - Hajj & Omra</div>
        <div class="document-title">REÇU DE PAIEMENT</div>
        <div class="receipt-number">N° {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    {{-- Payment Information --}}
    <div class="info-section">
        <div class="info-title">INFORMATIONS DU PAIEMENT</div>
        <div class="info-row">
            <div class="info-label">Date de paiement :</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Mode de paiement :</div>
            <div class="info-value">
                @switch($payment->payment_method)
                    @case('cash')
                        Espèces
                        @break
                    @case('check')
                        Chèque
                        @break
                    @case('bank_transfer')
                        Virement bancaire
                        @break
                    @case('card')
                        Carte bancaire
                        @break
                    @default
                        {{ $payment->payment_method }}
                @endswitch
            </div>
        </div>
        @if($payment->reference)
        <div class="info-row">
            <div class="info-label">Référence :</div>
            <div class="info-value">{{ $payment->reference }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Statut :</div>
            <div class="info-value">
                <span class="status-badge status-{{ $payment->status }}">
                    @switch($payment->status)
                        @case('completed')
                            Terminé
                            @break
                        @case('cancelled')
                            Annulé
                            @break
                        @default
                            En attente
                    @endswitch
                </span>
            </div>
        </div>
    </div>

    {{-- Amount --}}
    <div class="payment-amount">
        <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }}</div>
        <div class="currency">Dirhams (DH)</div>
    </div>

    {{-- Pilgrim Information --}}
    <div class="info-section">
        <div class="info-title">INFORMATIONS DU PÈLERIN</div>
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</div>
        </div>
        @if($payment->pilgrim->email)
        <div class="info-row">
            <div class="info-label">Email :</div>
            <div class="info-value">{{ $payment->pilgrim->email }}</div>
        </div>
        @endif
        @if($payment->pilgrim->phone)
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">{{ $payment->pilgrim->phone }}</div>
        </div>
        @endif
    </div>

    {{-- Campaign Information --}}
    @if($payment->pilgrim->campaign)
    <div class="info-section">
        <div class="info-title">INFORMATIONS DE LA CAMPAGNE</div>
        <div class="info-row">
            <div class="info-label">Campagne :</div>
            <div class="info-value">{{ $payment->pilgrim->campaign->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type :</div>
            <div class="info-value">{{ ucfirst($payment->pilgrim->campaign->type) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Départ :</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Retour :</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
        </div>
    </div>
    @endif

    {{-- Payment Summary --}}
    <div class="info-section">
        <div class="info-title">RÉSUMÉ FINANCIER</div>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Montant (DH)</th>
            </tr>
            <tr>
                <td>Montant total du voyage</td>
                <td class="text-right">{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>Total payé (avec ce paiement)</td>
                <td class="text-right">{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }}</td>
            </tr>
            <tr style="border-top: 2px solid #007bff;">
                <td><strong>Montant restant</strong></td>
                <td class="text-right"><strong>{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($payment->notes)
    <div class="info-section">
        <div class="info-title">NOTES</div>
        <div>{{ $payment->notes }}</div>
    </div>
    @endif

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Signature du Client</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Cachet et Signature de l'Agence</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>Ce reçu a été généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</div>
        <div class="mt-4">{{ config('app.name', 'Hajj Management') }} - Système de Gestion des Pèlerins</div>
        <div>Pour toute réclamation, veuillez présenter ce reçu.</div>
    </div>
</body>
</html>