<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Toutes les Campagnes</title>
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
        .section {
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
        .stats-grid {
            display: table;
            width: 100%;
        }
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'Hajj Management') }}</div>
        <div class="report-title">RAPPORT DE TOUTES LES CAMPAGNES</div>
        <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="section">
        <h4>📊 Statistiques Globales</h4>
        <div class="stats-grid">
            <div class="stats-cell">
                <strong>{{ $global_stats['total_campaigns'] }}</strong><br>
                Campagnes Totales
            </div>
            <div class="stats-cell">
                <strong>{{ $global_stats['total_pilgrims'] }}</strong><br>
                Pèlerins Totaux
            </div>
            <div class="stats-cell">
                <strong>{{ number_format($global_stats['total_revenue'], 0, ',', ' ') }} FCFA</strong><br>
                Revenus Totaux
            </div>
            <div class="stats-cell">
                <strong>{{ number_format($global_stats['pending_revenue'], 0, ',', ' ') }} FCFA</strong><br>
                Revenus En Attente
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Campagne</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Pèlerins</th>
                <th>Prix Classique</th>
                <th>Prix VIP</th>
                <th>Revenus Générés</th>
                <th>Revenus En Attente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $campaign)
            @php
                $totalRevenue = $campaign->pilgrims->sum('paid_amount');
                $pendingRevenue = $campaign->pilgrims->sum('remaining_amount');
            @endphp
            <tr>
                <td>
                    <strong>{{ $campaign->name }}</strong><br>
                    <small style="color: #666;">
                        {{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}
                    </small>
                </td>
                <td class="text-center">{{ ucfirst($campaign->type) }}</td>
                <td class="text-center">{{ ucfirst($campaign->status) }}</td>
                <td class="text-center">
                    <strong>{{ $campaign->pilgrims->count() }}</strong><br>
                    <small style="color: #666;">
                        C: {{ $campaign->pilgrims->where('category', 'classic')->count() }} |
                        V: {{ $campaign->pilgrims->where('category', 'vip')->count() }}
                    </small>
                </td>
                <td class="amount-cell">{{ number_format($campaign->price_classic, 0, ',', ' ') }}</td>
                <td class="amount-cell">{{ number_format($campaign->price_vip, 0, ',', ' ') }}</td>
                <td class="amount-cell">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</td>
                <td class="amount-cell">{{ number_format($pendingRevenue, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAUX:</td>
                <td class="amount-cell">{{ number_format($global_stats['total_revenue'], 0, ',', ' ') }} FCFA</td>
                <td class="amount-cell">{{ number_format($global_stats['pending_revenue'], 0, ',', ' ') }} FCFA</td>
            </tr>
        </tfoot>
    </table>

    <div class="section">
        <h4>📈 Analyse par Campagne</h4>
        @foreach($campaigns->take(5) as $campaign)
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #eee;">
            <strong>{{ $campaign->name }}</strong> - {{ $campaign->pilgrims->count() }} pèlerins<br>
            Revenus: {{ number_format($campaign->pilgrims->sum('paid_amount'), 0, ',', ' ') }} FCFA |
            Restant: {{ number_format($campaign->pilgrims->sum('remaining_amount'), 0, ',', ' ') }} FCFA
            @if($campaign->pilgrims->count() > 0)
            | Taux de paiement: {{ round(($campaign->pilgrims->sum('paid_amount') / ($campaign->pilgrims->sum('paid_amount') + $campaign->pilgrims->sum('remaining_amount'))) * 100) }}%
            @endif
        </div>
        @endforeach
    </div>

    <div class="footer">
        <div>{{ config('app.name', 'Hajj Management') }} - Système de Gestion</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>