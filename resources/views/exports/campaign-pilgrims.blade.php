<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Pèlerins - {{ $campaign->name }}</title>
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
        .campaign-info {
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
        <div class="report-title">LISTE DES PÈLERINS</div>
        <div>Campagne: {{ $campaign->name }}</div>
        <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="campaign-info">
        <strong>Informations Campagne:</strong><br>
        <strong>Nom:</strong> {{ $campaign->name }}<br>
        <strong>Type:</strong> {{ ucfirst($campaign->type) }}<br>
        <strong>Départ:</strong> {{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }}<br>
        <strong>Retour:</strong> {{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}<br>
        <strong>Prix Classique:</strong> {{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA<br>
        <strong>Prix VIP:</strong> {{ number_format($campaign->price_vip, 0, ',', ' ') }} FCFA
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Résumé:</strong>
        {{ $summary['total_pilgrims'] }} pèlerins |
        {{ $summary['classic_pilgrims'] }} classiques |
        {{ $summary['vip_pilgrims'] }} VIP |
        Revenus: {{ number_format($summary['total_revenue'], 0, ',', ' ') }} FCFA |
        Restant: {{ number_format($summary['remaining_amount'], 0, ',', ' ') }} FCFA
    </div>

    <table>
        <thead>
            <tr>
                <th>Client / Pèlerin</th>
                <th>Contact</th>
                <th>Catégorie</th>
                <th>Total</th>
                <th>Payé</th>
                <th>Reste</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pilgrims as $pilgrim)
            <tr>
                <td>
                    @if($pilgrim->client)
                        <strong>{{ $pilgrim->client->full_name }}</strong><br>
                        <small style="color: #666;">Pèlerin: {{ $pilgrim->full_name }}</small>
                    @else
                        {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}<br>
                        <small style="color: #dc3545;">⚠️ Client non associé</small>
                    @endif
                </td>
                <td>
                    @if($pilgrim->client)
                        {{ $pilgrim->client->email ?? $pilgrim->email ?? '-' }}<br>
                        {{ $pilgrim->client->phone ?? $pilgrim->phone ?? '-' }}
                    @else
                        {{ $pilgrim->email ?? '-' }}<br>
                        {{ $pilgrim->phone ?? '-' }}
                    @endif
                </td>
                <td class="text-center">{{ ucfirst($pilgrim->category ?? 'Standard') }}</td>
                <td class="amount-cell">{{ number_format($pilgrim->total_amount, 0, ',', ' ') }}</td>
                <td class="amount-cell">{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }}</td>
                <td class="amount-cell">{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }}</td>
                <td class="text-center">{{ strtoupper($pilgrim->status ?? 'ACTIF') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAUX:</td>
                <td class="amount-cell">{{ number_format($summary['total_revenue'] + $summary['remaining_amount'], 0, ',', ' ') }} FCFA</td>
                <td class="amount-cell">{{ number_format($summary['total_revenue'], 0, ',', ' ') }} FCFA</td>
                <td class="amount-cell">{{ number_format($summary['remaining_amount'], 0, ',', ' ') }} FCFA</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>{{ config('app.name', 'Hajj Management') }} - Système de Gestion</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>