@extends('layouts.app')

@section('title', 'Rapport des Clients')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Clients</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Rapport des Clients</h1>
        <p class="text-muted mb-0">Analyse détaillée de la base clients</p>
    </div>
    <div>
        <a href="{{ route('reports.index') }}" class="btn class="btn btn-outline-secondary "><i class="h-100">
            Retour aux Rapports
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card class="mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <x-form.select
                name="status"
                label="Statut"
                data-options="[
                    '' => 'Tous les statuts',
                    'active' => 'Actif',
                    'inactive' => 'Inactif'
                ]"
                value="request('status')"
            />
        </div>
        <div class="col-md-3">
            <input name="nationality" class="form-control"
                label="Nationalité"
                placeholder="Ex: Marocaine"
                value="request('nationality')"
            >
        </div>
        <div class="col-md-3">
            <input name="min_pilgrimages" class="form-control"
                label="Min. pèlerinages"
                type="number"
                min="1"
                value="request('min_pilgrimages')"
            >
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </button>
                @if(request()->hasAny(['status', 'nationality', 'min_pilgrimages']))
                    <a href="{{ route('reports.clients') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div></div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card class="h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $clientStats['total_clients'] }}</div>
                    <div class="text-white-75">Total Clients</div>
                </div>
                <i class="fas fa-users fa-2x text-white-25"></i>
            </div>
        </div></div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card class="h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $clientStats['active_clients'] }}</div>
                    <div class="text-white-75">Clients Actifs</div>
                </div>
                <i class="fas fa-user-check fa-2x text-white-25"></i>
            </div>
        </div></div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card class="h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $clientStats['clients_with_multiple_pilgrimages'] }}</div>
                    <div class="text-white-75">Clients Fidèles</div>
                </div>
                <i class="fas fa-star fa-2x text-white-25"></i>
            </div>
        </div></div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card class="h-100"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($clientStats['avg_pilgrimages_per_client'], 1) }}</div>
                    <div class="text-white-75">Moy. Pèlerinages/Client</div>
                </div>
                <i class="fas fa-chart-line fa-2x text-white-25"></i>
            </div>
        </div></div>
    </div>
</div>

<div class="row">
    <!-- Top Clients -->
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>🏆 Top 10 Clients par Revenus</h5></div><div class="card-body">
            @if($clientStats['top_clients']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Client</th>
                                <th>Pèlerinages</th>
                                <th>Revenus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientStats['top_clients'] as $index => $client)
                                @php
                                    $revenue = $client->pilgrims->sum(function($pilgrim) {
                                        return $pilgrim->payments->where('status', 'completed')->sum('amount');
                                    });
                                @endphp
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $client->full_name }}</strong>
                                        <br><small class="text-muted">{{ $client->phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $client->pilgrims->count() }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($revenue, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Aucune donnée disponible</p>
            @endif
        </div></div>
    </div>

    <!-- Client Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>📊 Répartition des Clients</h5></div><div class="card-body">
            <!-- By Pilgrimage Count -->
            <div class="mb-4">
                <h6>Par nombre de pèlerinages:</h6>
                @if(count($clientStats['clients_by_pilgrimage_count']) > 0)
                    @foreach($clientStats['clients_by_pilgrimage_count'] as $range => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $range }}</span>
                            <span class="badge bg-primary">{{ $count }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Aucune donnée</p>
                @endif
            </div>

            <!-- By Nationality -->
            @if(count($clientStats['by_nationality']) > 0)
                <div>
                    <h6>Par nationalité:</h6>
                    @foreach($clientStats['by_nationality'] as $nationality => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $nationality ?: 'Non spécifiée' }}</span>
                            <span class="badge bg-info">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div></div>
    </div>
</div>

<!-- Revenue Statistics -->
<div class="card class="mb-4"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>💰 Statistiques Financières</h5></div><div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="border-end pe-3">
                <h5>{{ number_format($clientStats['total_revenue_from_clients'], 0, ',', ' ') }} FCFA</h5>
                <p class="text-muted mb-0">Revenus totaux générés par les clients</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ps-3">
                <h5>{{ $clientStats['total_clients'] > 0 ? number_format($clientStats['total_revenue_from_clients'] / $clientStats['total_clients'], 0, ',', ' ') : 0 }} FCFA</h5>
                <p class="text-muted mb-0">Revenus moyens par client</p>
            </div>
        </div>
    </div>
</div></div>

<!-- Client List -->
@if($clients->count() > 0)
    <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>📋 Liste Détaillée des Clients</h5></div><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Nationalité</th>
                        <th>Pèlerinages</th>
                        <th>Revenus</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        @php
                            $revenue = $client->pilgrims->sum(function($pilgrim) {
                                return $pilgrim->payments->where('status', 'completed')->sum('amount');
                            });
                        @endphp
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $client->full_name }}</strong>
                                    <br><small class="text-muted">{{ $client->age }} ans</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($client->phone)
                                        <div><i class="fas fa-phone text-muted me-1"></i>{{ $client->phone }}</div>
                                    @endif
                                    @if($client->email)
                                        <div><i class="fas fa-envelope text-muted me-1"></i>{{ $client->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $client->nationality ?: 'Non spécifiée' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $client->pilgrims->count() }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($revenue, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $client->is_active ? 'success' : 'secondary' }}">
                                    {{ $client->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
@else
    <div class="card"><div class="card-body">
        <div class="text-center py-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5>Aucun client trouvé</h5>
            <p class="text-muted">Aucun client ne correspond aux critères sélectionnés.</p>
        </div>
    </div></div>
@endif
@endsection