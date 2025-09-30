<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Pèlerins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #495057;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .progress-bar {
            width: 100px;
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            display: inline-block;
        }
        .progress-fill {
            height: 100%;
            background: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Pèlerins</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        @if($filters['from_date'] || $filters['to_date'])
            <p>
                Période:
                @if($filters['from_date'])
                    du {{ Carbon\Carbon::parse($filters['from_date'])->format('d/m/Y') }}
                @endif
                @if($filters['to_date'])
                    au {{ Carbon\Carbon::parse($filters['to_date'])->format('d/m/Y') }}
                @endif
            </p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total_pilgrims'] }}</div>
            <div class="stat-label">Total Pèlerins</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['classic_pilgrims'] }}</div>
            <div class="stat-label">Classic</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['vip_pilgrims'] }}</div>
            <div class="stat-label">VIP</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['active_pilgrims'] }}</div>
            <div class="stat-label">Actifs</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Revenus Collectés</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['completion_rate'] }}%</div>
            <div class="stat-label">Taux Completion</div>
        </div>
    </div>

    <h3>Résumé Financier</h3>
    <table style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Montant Attendu</th>
                <th>Montant Collecté</th>
                <th>Restant à Collecter</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Total</strong></td>
                <td>{{ number_format($stats['expected_revenue'], 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($stats['remaining_revenue'], 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <h3>Liste des Pèlerins</h3>
    <table>
        <thead>
            <tr>
                <th>Nom Complet</th>
                <th>Client</th>
                <th>Campagne</th>
                <th>Catégorie</th>
                <th>Téléphone</th>
                <th>Montant Total</th>
                <th>Montant Payé</th>
                <th>Restant</th>
                <th>Progression</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pilgrims as $pilgrim)
                @php
                    $progressPercent = $pilgrim->total_amount > 0 ? round(($pilgrim->paid_amount / $pilgrim->total_amount) * 100) : 0;
                    $statusClass = match($pilgrim->status) {
                        'confirmed' => 'badge-success',
                        'pending' => 'badge-warning',
                        'cancelled' => 'badge-secondary',
                        default => 'badge-info'
                    };
                @endphp
                <tr>
                    <td>{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</td>
                    <td>{{ $pilgrim->client ? $pilgrim->client->full_name : '-' }}</td>
                    <td>{{ $pilgrim->campaign ? $pilgrim->campaign->name : '-' }}</td>
                    <td>
                        <span class="badge {{ $pilgrim->category === 'vip' ? 'badge-info' : 'badge-secondary' }}">
                            {{ ucfirst($pilgrim->category) }}
                        </span>
                    </td>
                    <td>{{ $pilgrim->phone ?: '-' }}</td>
                    <td>{{ number_format($pilgrim->total_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} FCFA</td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        {{ $progressPercent }}%
                    </td>
                    <td>
                        <span class="badge {{ $statusClass }}">
                            {{ ucfirst($pilgrim->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré par le Système de Gestion Hajj - {{ config('app.name') }}</p>
    </div>
</body>
</html>