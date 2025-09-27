<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Clients - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #6c757d;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Système de Gestion Hajj</h1>
        <p>Export de la liste des clients</p>
        <p>Généré le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <div class="summary">
        <strong>Résumé:</strong>
        <ul style="margin: 10px 0;">
            <li>Nombre total de clients: {{ $clients->count() }}</li>
            <li>Clients actifs: {{ $clients->where('is_active', true)->count() }}</li>
            <li>Clients inactifs: {{ $clients->where('is_active', false)->count() }}</li>
            <li>Total des pèlerinages: {{ $clients->sum(function($client) { return $client->pilgrims->count(); }) }}</li>
        </ul>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom complet</th>
                <th>Sexe</th>
                <th>Âge</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Nationalité</th>
                <th>Région</th>
                <th>Statut</th>
                <th>Pèlerinages</th>
                <th>Date création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->full_name }}</td>
                <td>{{ $client->gender === 'male' ? 'H' : 'F' }}</td>
                <td>{{ $client->age ?? '-' }}</td>
                <td>{{ $client->phone ?? '-' }}</td>
                <td>{{ $client->email ? Str::limit($client->email, 25) : '-' }}</td>
                <td>{{ $client->nationality ?? '-' }}</td>
                <td>{{ $client->region ?? '-' }}</td>
                <td class="{{ $client->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $client->is_active ? 'Actif' : 'Inactif' }}
                </td>
                <td>{{ $client->pilgrims->count() }}</td>
                <td>{{ $client->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($clients->count() === 0)
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="font-size: 16px;">Aucun client trouvé pour les critères sélectionnés.</p>
        </div>
    @endif

    <div class="footer">
        <p>
            Document généré automatiquement par le Système de Gestion Hajj<br>
            {{ config('app.name') }} - {{ date('Y') }}
        </p>
    </div>
</body>
</html>