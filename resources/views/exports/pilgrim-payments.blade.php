<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements - {{ $pilgrim->full_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            color: #333;
        }
        .pilgrim-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 8px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .amount-cell {
            text-align: right;
            font-weight: bold;
        }
        .status-completed { color: #28a745; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
        .status-cancelled { color: #dc3545; font-weight: bold; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'Hajj Management') }}</div>
        <div class="report-title">HISTORIQUE DES PAIEMENTS</div>
        <div>
            @if($pilgrim->client)
                Client: {{ $pilgrim->client->full_name }} | Pèlerin: {{ $pilgrim->full_name }}
            @else
                Pèlerin: {{ $pilgrim->full_name }}
            @endif
        </div>
        <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="pilgrim-info">
        <strong>Informations:</strong><br>
        @if($pilgrim->client)
            <strong>Client:</strong> {{ $client->full_name }}<br>
            @if($client->email)
                <strong>Email Client:</strong> {{ $client->email }}<br>
            @endif
            @if($client->phone)
                <strong>Téléphone Client:</strong> {{ $client->phone }}<br>
            @endif
            <strong>Pèlerin:</strong> {{ $pilgrim->full_name }}<br>
        @else
            <strong>Nom:</strong> {{ $pilgrim->full_name }}<br>
            @if($pilgrim->email)
                <strong>Email:</strong> {{ $pilgrim->email }}<br>
            @endif
            @if($pilgrim->phone)
                <strong>Téléphone:</strong> {{ $pilgrim->phone }}<br>
            @endif
        @endif
        <strong>Campagne:</strong> {{ $campaign->name ?? 'N/A' }}<br>
        <strong>Catégorie:</strong> {{ ucfirst($pilgrim->category ?? 'Standard') }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Résumé Financier:</strong>
        Total Payé: {{ number_format($summary['total_paid'], 0, ',', ' ') }} FCFA |
        En Attente: {{ number_format($summary['total_pending'], 0, ',', ' ') }} FCFA |
        Restant: {{ number_format($summary['remaining'], 0, ',', ' ') }} FCFA
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant</th>
                <th>Mode de Paiement</th>
                <th>Référence</th>
                <th>Statut</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td class="amount-cell">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                <td class="text-center">
                    @switch($payment->payment_method)
                        @case('cash') Espèces @break
                        @case('check') Chèque @break
                        @case('bank_transfer') Virement @break
                        @case('card') Carte @break
                        @default {{ $payment->payment_method }}
                    @endswitch
                </td>
                <td>{{ $payment->reference ?? '-' }}</td>
                <td class="text-center status-{{ $payment->status }}">
                    @switch($payment->status)
                        @case('completed') TERMINÉ @break
                        @case('cancelled') ANNULÉ @break
                        @default EN ATTENTE
                    @endswitch
                </td>
                <td>{{ $payment->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td class="text-right">TOTAL:</td>
                <td class="amount-cell">{{ number_format($payments->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>{{ config('app.name', 'Hajj Management') }} - Système de Gestion</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>