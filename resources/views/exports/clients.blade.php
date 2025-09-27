<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Clients</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Clients</h1>
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
            <div class="stat-number">{{ $stats['total_clients'] }}</div>
            <div class="stat-label">Total Clients</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['active_clients'] }}</div>
            <div class="stat-label">Clients Actifs</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total_pilgrimages'] }}</div>
            <div class="stat-label">Total Pèlerinages</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($stats['total_spent'], 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Montant Total</div>
        </div>
    </div>

    @if($stats['nationality_breakdown']->count() > 0)
        <h3>Répartition par Nationalité</h3>
        <table style="width: 50%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Nationalité</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['nationality_breakdown'] as $nationality => $count)
                    <tr>
                        <td>{{ $nationality ?: 'Non renseignée' }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($stats['region_breakdown']->count() > 0)
        <h3>Répartition par Région (Cameroun)</h3>
        <table style="width: 50%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Région</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['region_breakdown'] as $region => $count)
                    <tr>
                        <td>{{ $region ?: 'Non renseignée' }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Liste des Clients</h3>
    <table>
        <thead>
            <tr>
                <th>Nom Complet</th>
                <th>Genre</th>
                <th>Âge</th>
                <th>Téléphone</th>
                <th>Nationalité</th>
                <th>Région</th>
                <th>Nb Pèlerinages</th>
                <th>Total Dépensé</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->full_name }}</td>
                    <td>{{ $client->gender === 'male' ? 'H' : 'F' }}</td>
                    <td>{{ $client->age }}</td>
                    <td>{{ $client->phone ?: '-' }}</td>
                    <td>{{ $client->nationality ?: '-' }}</td>
                    <td>{{ $client->region ?: '-' }}</td>
                    <td>{{ $client->pilgrims->count() }}</td>
                    <td>{{ number_format($client->pilgrims->sum('paid_amount'), 0, ',', ' ') }} FCFA</td>
                    <td>
                        <span class="badge {{ $client->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $client->is_active ? 'Actif' : 'Inactif' }}
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