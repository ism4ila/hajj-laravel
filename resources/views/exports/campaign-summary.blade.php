<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé Campagne - {{ $campaign->name }}</title>
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
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        .stat-item {
            border-bottom: 1px solid #eee;
            padding: 5px 0;
            display: flex;
            justify-content: space-between;
        }
        .stat-label {
            font-weight: bold;
        }
        .stat-value {
            color: #007bff;
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
        <div class="report-title">RÉSUMÉ DE CAMPAGNE</div>
        <div>{{ $campaign->name }}</div>
        <div>Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="section">
        <strong>Informations Campagne:</strong><br>
        <strong>Nom:</strong> {{ $campaign->name }}<br>
        <strong>Type:</strong> {{ ucfirst($campaign->type) }}<br>
        <strong>Statut:</strong> {{ ucfirst($campaign->status) }}<br>
        <strong>Départ:</strong> {{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }}<br>
        <strong>Retour:</strong> {{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}<br>
        <strong>Prix Classique:</strong> {{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA<br>
        <strong>Prix VIP:</strong> {{ number_format($campaign->price_vip, 0, ',', ' ') }} FCFA
    </div>

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <h4>📊 Statistiques Générales</h4>
                <div class="stat-item">
                    <span class="stat-label">Total Pèlerins:</span>
                    <span class="stat-value">{{ $statistics['total_pilgrims'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Pèlerins Classiques:</span>
                    <span class="stat-value">{{ $statistics['classic_pilgrims'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Pèlerins VIP:</span>
                    <span class="stat-value">{{ $statistics['vip_pilgrims'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Taux Completion:</span>
                    <span class="stat-value">{{ $statistics['completion_rate'] }}%</span>
                </div>
            </div>
            <div class="stats-cell">
                <h4>💰 Statistiques Financières</h4>
                <div class="stat-item">
                    <span class="stat-label">Revenus Attendus:</span>
                    <span class="stat-value">{{ number_format($statistics['expected_revenue'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Revenus Réels:</span>
                    <span class="stat-value">{{ number_format($statistics['actual_revenue'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Paiements En Attente:</span>
                    <span class="stat-value">{{ number_format($statistics['pending_payments'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Restant à Collecter:</span>
                    <span class="stat-value">{{ number_format($statistics['remaining_to_collect'], 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h4>📈 Répartition par Catégorie</h4>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <strong>Catégorie Classique:</strong><br>
                    Nombre: {{ $category_breakdown['classic']['count'] }}<br>
                    Attendu: {{ number_format($category_breakdown['classic']['expected'], 0, ',', ' ') }} FCFA<br>
                    Collecté: {{ number_format($category_breakdown['classic']['collected'], 0, ',', ' ') }} FCFA
                </div>
                <div class="stats-cell">
                    <strong>Catégorie VIP:</strong><br>
                    Nombre: {{ $category_breakdown['vip']['count'] }}<br>
                    Attendu: {{ number_format($category_breakdown['vip']['expected'], 0, ',', ' ') }} FCFA<br>
                    Collecté: {{ number_format($category_breakdown['vip']['collected'], 0, ',', ' ') }} FCFA
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div>{{ config('app.name', 'Hajj Management') }} - Système de Gestion</div>
        <div>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</body>
</html>