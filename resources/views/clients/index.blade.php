@extends('layouts.app')

@section('title', 'Gestion des Clients')

@section('breadcrumb')
    <li class="breadcrumb-item active">Clients</li>
@endsection

@section('content')
<div class="client-management">
    <!-- Page Header responsive -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="bg-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
                    <i class="fas fa-users text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h1 class="text-responsive-xl mb-1 fw-bold">Gestion des Clients</h1>
                    <p class="text-muted mb-0 text-responsive-sm">
                        <i class="fas fa-info-circle me-1"></i>
                        Gérez vos clients et suivez leur parcours pèlerin
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end justify-content-md-end justify-content-sm-center gap-2 flex-wrap">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download me-1"></i>
                    <span class="d-none d-md-inline">Exporter</span>
                    <span class="d-md-none">Export</span>
                </button>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>
                    <span class="d-none d-sm-inline">Nouveau Client</span>
                    <span class="d-sm-none">Nouveau</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards responsive -->
    <div class="stats-grid mb-4" id="statsCards">
        <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted text-responsive-xs mb-1 text-uppercase fw-semibold">TOTAL CLIENTS</div>
                        <div class="text-responsive-xl mb-0 fw-bold text-primary counter" data-target="{{ $clients->total() }}">0</div>
                        <div class="text-responsive-xs text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span>Registres clients</span>
                        </div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 50px; min-height: 50px;">
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted text-responsive-xs mb-1 text-uppercase fw-semibold">CLIENTS ACTIFS</div>
                        <div class="text-responsive-xl mb-0 fw-bold text-success counter" data-target="{{ $clients->where('is_active', true)->count() }}">0</div>
                        <div class="text-responsive-xs text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            <span>En activité</span>
                        </div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 50px; min-height: 50px;">
                        <i class="fas fa-user-check fa-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted text-responsive-xs mb-1 text-uppercase fw-semibold">PÈLERINAGES</div>
                        <div class="text-responsive-xl mb-0 fw-bold text-info counter" data-target="{{ $clients->sum(function($c) { return $c->pilgrims->count(); }) }}">0</div>
                        <div class="text-responsive-xs text-info">
                            <i class="fas fa-route me-1"></i>
                            <span>Voyages sacrés</span>
                        </div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 50px; min-height: 50px;">
                        <i class="fas fa-route fa-lg text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted text-responsive-xs mb-1 text-uppercase fw-semibold">FIDÉLITÉ</div>
                        <div class="text-responsive-xl mb-0 fw-bold text-warning counter" data-target="{{ number_format($clients->filter(function($c) { return $c->pilgrims->count() > 1; })->count() / max($clients->count(), 1) * 100, 1) }}">0</div>
                        <div class="text-responsive-xs text-warning">
                            <i class="fas fa-star me-1"></i>
                            <span>% clients fidèles</span>
                        </div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 50px; min-height: 50px;">
                        <i class="fas fa-star fa-lg text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Filtres avec design moderne -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0">
        <div class="d-flex align-items-center">
            <i class="fas fa-filter text-primary me-2"></i>
            <h6 class="mb-0 fw-semibold">Filtres et Recherche</h6>
            <button class="btn btn-sm btn-outline-secondary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
    <div class="collapse show" id="filtersCollapse">
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="row g-3" id="filterForm">
                <div class="col-lg-5 col-md-6">
                    <label for="search" class="form-label fw-semibold">
                        <i class="fas fa-search me-1 text-primary"></i>Recherche globale
                    </label>
                    <div class="input-group">
                        <input type="text"
                               class="form-control border-2"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Tapez un nom, email, téléphone..."
                               autocomplete="off">
                        <button class="btn btn-outline-primary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="form-text">Recherche en temps réel activée</div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="status" class="form-label fw-semibold">
                        <i class="fas fa-toggle-on me-1 text-success"></i>Statut
                    </label>
                    <select name="status" id="status" class="form-select border-2">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            <span class="text-success">●</span> Actif
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            <span class="text-muted">●</span> Inactif
                        </option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3">
                    <label for="sort" class="form-label fw-semibold">
                        <i class="fas fa-sort me-1 text-info"></i>Trier par
                    </label>
                    <select name="sort" id="sort" class="form-select border-2">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom A-Z</option>
                        <option value="date" {{ request('sort') === 'date' ? 'selected' : '' }}>Date création</option>
                        <option value="pilgrimages" {{ request('sort') === 'pilgrimages' ? 'selected' : '' }}>Nb. pèlerinages</option>
                    </select>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <div class="d-grid w-100 gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->hasAny(['search', 'status', 'sort']))
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Filtres actifs :
                            @if(request('search'))
                                <span class="badge bg-primary">Recherche: "{{ request('search') }}"</span>
                            @endif
                            @if(request('status'))
                                <span class="badge bg-success">Statut: {{ request('status') === 'active' ? 'Actif' : 'Inactif' }}</span>
                            @endif
                            @if(request('sort'))
                                <span class="badge bg-info">Tri: {{ ucfirst(request('sort')) }}</span>
                            @endif
                        </div>
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Clients List -->
@if($clients->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pb-0">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Liste des Clients ({{ $clients->total() }})
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" id="toggleView" data-view="card">
                        <i class="fas fa-th-large me-1"></i>Vue Cartes
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('clients.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel me-2 text-success"></i>Excel (.xlsx)</a></li>
                            <li><a class="dropdown-item" href="{{ route('clients.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF (.pdf)</a></li>
                            <li><a class="dropdown-item" href="{{ route('clients.export', ['format' => 'csv']) }}"><i class="fas fa-file-csv me-2 text-info"></i>CSV (.csv)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div class="card-body border-bottom bg-light py-2" id="bulkActionsBar" style="display: none;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">
                        <span id="selectedCount">0</span> client(s) sélectionné(s)
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-success" onclick="bulkActivate()">
                        <i class="fas fa-check me-1"></i>Activer
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="bulkDeactivate()">
                        <i class="fas fa-pause me-1"></i>Désactiver
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="bulkExport()">
                        <i class="fas fa-download me-1"></i>Exporter
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="clientsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-semibold">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-normal" for="selectAll">Client</label>
                                </div>
                            </th>
                            <th class="d-none d-md-table-cell border-0 fw-semibold">Contact</th>
                            <th class="d-none d-lg-table-cell border-0 fw-semibold">Pèlerinages</th>
                            <th class="border-0 fw-semibold">Statut</th>
                            <th class="text-center border-0 fw-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr class="client-row" data-client-id="{{ $client->id }}">
                            <!-- Client Info avec sélection -->
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                        <input class="form-check-input client-checkbox" type="checkbox" value="{{ $client->id }}">
                                    </div>
                                    <div class="position-relative me-3">
                                        <div class="avatar bg-gradient-primary text-white rounded-circle shadow-sm"
                                             style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            {{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}
                                        </div>
                                        @if($client->is_active)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 0.5rem;">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark mb-1">
                                            <a href="{{ route('clients.show', $client) }}" class="text-decoration-none text-dark hover-primary">
                                                {{ $client->full_name }}
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="badge bg-light text-dark">{{ $client->age }} ans</span>
                                            <span class="text-{{ $client->gender === 'male' ? 'primary' : 'danger' }}">
                                                {{ $client->gender === 'male' ? '♂' : '♀' }}
                                            </span>
                                            @if($client->nationality)
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    <i class="fas fa-flag me-1"></i>{{ $client->nationality }}
                                                </span>
                                                @if($client->nationality === 'Cameroun' && $client->region)
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $client->region }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        <!-- Mobile Contact Info -->
                                        <div class="d-md-none mt-2">
                                            @if($client->phone)
                                                <div class="small text-muted mb-1">
                                                    <i class="fas fa-phone text-success me-1"></i>
                                                    <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                                </div>
                                            @endif
                                            @if($client->email)
                                                <div class="small text-muted">
                                                    <i class="fas fa-envelope text-primary me-1"></i>
                                                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact (Hidden on mobile) -->
                            <td class="d-none d-md-table-cell align-middle">
                                @if($client->phone || $client->email)
                                    @if($client->phone)
                                        <div class="small mb-1">
                                            <i class="fas fa-phone text-success me-2"></i>
                                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                        </div>
                                    @endif
                                    @if($client->email)
                                        <div class="small">
                                            <i class="fas fa-envelope text-primary me-2"></i>
                                            <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ Str::limit($client->email, 20) }}</a>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small">
                                        <i class="fas fa-minus"></i> Non renseigné
                                    </span>
                                @endif
                            </td>

                            <!-- Pilgrimages (Hidden on tablet) -->
                            <td class="d-none d-lg-table-cell align-middle">
                                <div class="text-center">
                                    @if($client->pilgrims->count() > 0)
                                        <div class="position-relative d-inline-block">
                                            <div class="h4 mb-0 text-primary fw-bold">{{ $client->pilgrims->count() }}</div>
                                            <small class="text-muted">pèlerinages</small>
                                            @if($client->pilgrims->count() > 1)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                                    <i class="fas fa-star" style="font-size: 0.6rem;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-minus"></i>
                                            <br><small>Aucun</small>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="align-middle">
                                <div class="d-flex flex-column align-items-start">
                                    @if($client->is_active)
                                        <span class="badge bg-success bg-opacity-15 text-success border border-success">
                                            <i class="fas fa-check-circle me-1"></i>Actif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-15 text-secondary border border-secondary">
                                            <i class="fas fa-pause-circle me-1"></i>Inactif
                                        </span>
                                    @endif

                                    <!-- Mobile Pilgrimages Count -->
                                    <div class="d-lg-none mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-route me-1"></i>{{ $client->pilgrims->count() }} pèlerinages
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions responsive -->
                            <td class="align-middle">
                                <div class="d-flex justify-content-center action-buttons">
                                    <a href="{{ route('clients.show', $client) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir le profil">
                                        <i class="fas fa-eye"></i>
                                        <span class="d-none d-xl-inline ms-1">Voir</span>
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-xl-inline ms-1">Modifier</span>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                data-bs-toggle="tooltip"
                                                title="Plus d'actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <span class="d-none d-xl-inline ms-1">Plus</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <h6 class="dropdown-header text-primary">
                                                    <i class="fas fa-user me-2"></i>{{ Str::limit($client->full_name, 20) }}
                                                </h6>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('clients.show', $client) }}">
                                                    <i class="fas fa-eye me-2 text-primary"></i>
                                                    <span>Voir le profil complet</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('clients.edit', $client) }}">
                                                    <i class="fas fa-edit me-2 text-warning"></i>
                                                    <span>Modifier les informations</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}">
                                                    <i class="fas fa-plus me-2 text-success"></i>
                                                    <span>Nouveau pèlerinage</span>
                                                </a>
                                            </li>
                                            @if($client->pilgrims->count() === 0)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                          onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement ce client ?')"
                                                          class="d-inline w-100">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                                            <i class="fas fa-trash me-2"></i>
                                                            <span>Supprimer définitivement</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center">
                        {{ $clients->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users text-muted fa-3x mb-3"></i>
            <h4>Aucun client trouvé</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'status', 'sort']))
                    Aucun client ne correspond à vos critères de recherche.
                    <br>
                    <a href="{{ route('clients.index') }}" class="btn btn-link">Voir tous les clients</a>
                @else
                    Commencez par ajouter votre premier client.
                @endif
            </p>

            @if(!request()->hasAny(['search', 'status', 'sort']))
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>Ajouter un client
                </a>
            @endif
        </div>
    </div>
@endif

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exporter les Clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('exports.clients') }}">

                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format d'export</label>
                        <select class="form-select" name="format" id="export_format" required>
                            <option value="">Choisir un format</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Période</label>
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control" name="from_date" placeholder="Date début">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="to_date" placeholder="Date fin">
                            </div>
                        </div>
                        <div class="form-text">Laissez vide pour exporter tous les clients</div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-1"></i>Exporter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* RESPONSIVE STYLES POUR GESTION CLIENTS */
    .client-management {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }

    /* Header responsive */
    @media (max-width: 768px) {
        .col-lg-8, .col-lg-4 {
            text-align: center;
            margin-bottom: var(--spacing-sm);
        }

        .btn-group {
            width: 100%;
            display: flex;
            gap: var(--spacing-xs);
        }

        .btn-group .btn {
            flex: 1;
        }
    }

    /* Stats cards responsive */
    .stat-card {
        transition: all var(--transition-normal);
        cursor: pointer;
        border-radius: 0.75rem;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
    }

    @media (max-width: 992px) {
        .stat-card .card-body {
            padding: var(--spacing-sm);
        }

        .stat-card .h3 {
            font-size: var(--font-size-lg);
        }
    }

    @media (max-width: 576px) {
        .stat-card .d-flex {
            flex-direction: column;
            text-align: center;
        }

        .stat-card .bg-opacity-10 {
            align-self: center;
            margin-top: var(--spacing-xs);
        }
    }

    /* Filtres responsive */
    .filters-section {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .filters-section .row.g-3 {
            --bs-gutter-x: 0.5rem;
        }

        .filters-section .col-lg-5,
        .filters-section .col-lg-2,
        .filters-section .col-lg-3 {
            margin-bottom: var(--spacing-sm);
        }
    }

    @media (max-width: 576px) {
        .filters-section .input-group .btn {
            min-width: 44px;
        }
    }

    /* Table responsive améliorée */
    .client-row {
        transition: all var(--transition-fast);
    }

    .client-row:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.005);
    }

    @media (max-width: 768px) {
        .client-row:hover {
            transform: none; /* Pas de scale sur mobile */
        }

        .table-responsive {
            border-radius: 0.75rem;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            padding: var(--spacing-xs);
            font-size: var(--font-size-sm);
        }
    }

    @media (max-width: 576px) {
        .avatar {
            width: 35px !important;
            height: 35px !important;
            font-size: var(--font-size-xs);
        }

        .table th,
        .table td {
            padding: 0.25rem;
            font-size: var(--font-size-xs);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: var(--font-size-xs);
        }
    }

    /* Actions responsive */
    .action-buttons {
        gap: 0.25rem;
    }

    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.125rem;
        }

        .action-buttons .btn {
            width: 100%;
            min-height: 44px;
        }

        .dropdown-menu {
            min-width: auto;
            width: 100%;
        }
    }

    /* Bulk actions responsive */
    .bulk-actions-bar {
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    @media (max-width: 768px) {
        .bulk-actions-bar .d-flex {
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .bulk-actions-bar .d-flex.gap-2 {
            flex-direction: row;
            justify-content: center;
        }
    }

    /* Modal responsive */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-content {
            border-radius: 0.75rem;
        }
    }

    /* Hover effects */
    .hover-primary:hover {
        color: var(--bs-primary) !important;
        transition: color var(--transition-fast);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--bs-primary), #4dabf7);
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Toast notifications responsive */
    @media (max-width: 576px) {
        .toast {
            width: calc(100vw - 2rem) !important;
            max-width: none !important;
        }
    }

    /* Print styles */
    @media print {
        .no-print,
        .btn,
        .dropdown,
        .bulk-actions-bar {
            display: none !important;
        }

        .card {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .table {
            font-size: 10pt;
        }
    }

    /* Dark mode support (si nécessaire) */
    @media (prefers-color-scheme: dark) {
        .stat-card {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .client-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    }

    /* Touch improvements */
    @media (pointer: coarse) {
        .btn {
            min-height: 44px;
            min-width: 44px;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .card {
            border: 2px solid #000;
        }

        .btn-outline-primary {
            border-width: 2px;
        }

        .badge {
            border: 1px solid currentColor;
        }
    }
</style>
@endpush

@push('scripts')
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Animation des compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseFloat(counter.getAttribute('data-target'));
            const duration = 2000; // 2 secondes
            const steps = 60;
            const stepValue = target / steps;
            let current = 0;

            const timer = setInterval(() => {
                current += stepValue;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }

                // Formater selon le type de valeur
                if (counter.getAttribute('data-target').includes('.')) {
                    counter.textContent = current.toFixed(1);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, duration / steps);
        });
    }

    // Observer pour déclencher l'animation des compteurs
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    });

    const statsSection = document.getElementById('statsCards');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Gestion de la sélection multiple
    const selectAllCheckbox = document.getElementById('selectAll');
    const clientCheckboxes = document.querySelectorAll('.client-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            clientCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                toggleRowSelection(checkbox.closest('tr'), this.checked);
            });
            updateBulkActions();
        });
    }

    clientCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleRowSelection(this.closest('tr'), this.checked);
            updateSelectAll();
            updateBulkActions();
        });
    });

    function toggleRowSelection(row, selected) {
        if (selected) {
            row.classList.add('table-primary');
        } else {
            row.classList.remove('table-primary');
        }
    }

    function updateSelectAll() {
        const checkedCount = document.querySelectorAll('.client-checkbox:checked').length;
        const totalCount = clientCheckboxes.length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === totalCount;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }
    }

    function updateBulkActions() {
        const selectedCount = document.querySelectorAll('.client-checkbox:checked').length;
        // Ici on peut ajouter des actions en lot
        console.log(`${selectedCount} clients sélectionnés`);
    }

    // Recherche en temps réel
    const searchInput = document.getElementById('search');
    const clearSearchBtn = document.getElementById('clearSearch');
    let searchTimeout;

    if (searchInput && clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 300);
        });
    }

    // Fonction de recherche AJAX
    function performSearch() {
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const sortSelect = document.getElementById('sort');
        const tableBody = document.querySelector('#clientsTable tbody');
        const paginationContainer = document.querySelector('.card-footer');
        const resultsInfo = document.querySelector('.results-info');

        if (!searchInput || !tableBody) return;

        // Préparer les données
        const formData = new FormData();
        formData.append('search', searchInput.value);
        formData.append('status', statusSelect ? statusSelect.value : '');
        formData.append('sort', sortSelect ? sortSelect.value : 'name');
        formData.append('table_search', '1');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Afficher l'indicateur de chargement
        showLoadingState(tableBody);

        // Faire la requête AJAX
        fetch('{{ route("clients.search") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Remplacer le contenu du tableau
            tableBody.innerHTML = data.html;

            // Mettre à jour la pagination
            if (paginationContainer) {
                if (data.pagination) {
                    paginationContainer.innerHTML = '<div class="d-flex justify-content-center">' + data.pagination + '</div>';
                    paginationContainer.style.display = 'block';
                } else {
                    paginationContainer.style.display = 'none';
                }
            }

            // Mettre à jour les informations de résultats
            updateResultsInfo(data.showing);

            // Réinitialiser les tooltips
            reinitializeTooltips();

            // Réinitialiser les checkboxes
            reinitializeCheckboxes();
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            showErrorState(tableBody);
        });
    }

    // Afficher l'état de chargement
    function showLoadingState(tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <span class="text-muted">Recherche en cours...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    // Afficher l'état d'erreur
    function showErrorState(tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Une erreur s'est produite lors de la recherche.
                        <button class="btn btn-link btn-sm" onclick="performSearch()">Réessayer</button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Mettre à jour les informations de résultats
    function updateResultsInfo(showing) {
        let infoElement = document.querySelector('.results-info');
        if (!infoElement) {
            // Créer l'élément s'il n'existe pas
            const cardBody = document.querySelector('.card-body');
            if (cardBody) {
                infoElement = document.createElement('div');
                infoElement.className = 'results-info text-muted small mt-2';
                cardBody.appendChild(infoElement);
            }
        }

        if (infoElement && showing) {
            infoElement.innerHTML = `
                <i class="fas fa-info-circle me-1"></i>
                Affichage de ${showing.from} à ${showing.to} sur ${showing.total} résultats
            `;
        }
    }

    // Réinitialiser les tooltips
    function reinitializeTooltips() {
        // Détruire les tooltips existants
        const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        existingTooltips.forEach(el => {
            const tooltip = bootstrap.Tooltip.getInstance(el);
            if (tooltip) tooltip.dispose();
        });

        // Créer de nouveaux tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Réinitialiser les checkboxes
    function reinitializeCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const clientCheckboxes = document.querySelectorAll('.client-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.addEventListener('change', function() {
                clientCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsVisibility();
            });
        }

        clientCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = [...clientCheckboxes].every(cb => cb.checked);
                const someChecked = [...clientCheckboxes].some(cb => cb.checked);

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
                updateBulkActionsVisibility();
            });
        });
    }

    // Écouter les changements de filtres
    document.addEventListener('change', function(e) {
        if (e.target.id === 'status' || e.target.id === 'sort') {
            performSearch();
        }
    });

    // Tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Collapse animation pour les filtres
    const filtersToggle = document.querySelector('[data-bs-target="#filtersCollapse"]');
    const filtersCollapse = document.getElementById('filtersCollapse');

    if (filtersToggle && filtersCollapse) {
        filtersCollapse.addEventListener('show.bs.collapse', function() {
            filtersToggle.querySelector('i').style.transform = 'rotate(180deg)';
        });

        filtersCollapse.addEventListener('hide.bs.collapse', function() {
            filtersToggle.querySelector('i').style.transform = 'rotate(0deg)';
        });
    }

    // Animation d'apparition pour les lignes du tableau
    const tableRows = document.querySelectorAll('.client-row');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 50}ms`;
        row.classList.add('fade-in');
    });

    // Initialiser les checkboxes au chargement
    reinitializeCheckboxes();
});

// Fonctions pour les actions en lot
function updateBulkActionsVisibility() {
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const checkboxes = document.querySelectorAll('.client-checkbox:checked');

    if (checkboxes.length > 0) {
        bulkActionsBar.style.display = 'block';
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActionsBar.style.display = 'none';
    }
}

function getSelectedClientIds() {
    const checkboxes = document.querySelectorAll('.client-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

function bulkActivate() {
    const clientIds = getSelectedClientIds();
    if (clientIds.length === 0) return;

    if (confirm(`Êtes-vous sûr de vouloir activer ${clientIds.length} client(s) ?`)) {
        bulkAction('activate', clientIds);
    }
}

function bulkDeactivate() {
    const clientIds = getSelectedClientIds();
    if (clientIds.length === 0) return;

    if (confirm(`Êtes-vous sûr de vouloir désactiver ${clientIds.length} client(s) ?`)) {
        bulkAction('deactivate', clientIds);
    }
}

function bulkDelete() {
    const clientIds = getSelectedClientIds();
    if (clientIds.length === 0) return;

    if (confirm(`⚠️ ATTENTION: Êtes-vous sûr de vouloir supprimer définitivement ${clientIds.length} client(s) ?\n\nCette action est irréversible !`)) {
        bulkAction('delete', clientIds);
    }
}

function bulkExport() {
    const clientIds = getSelectedClientIds();
    if (clientIds.length === 0) return;

    const format = prompt('Format d\'export (excel, pdf, csv):', 'excel');
    if (format && ['excel', 'pdf', 'csv'].includes(format.toLowerCase())) {
        const url = `{{ route('clients.export') }}?format=${format}&clients=${clientIds.join(',')}`;
        window.open(url, '_blank');
    }
}

function bulkAction(action, clientIds) {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('client_ids', JSON.stringify(clientIds));
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Afficher un indicateur de chargement
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const originalContent = bulkActionsBar.innerHTML;
    bulkActionsBar.innerHTML = `
        <div class="d-flex align-items-center justify-content-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            <span>Traitement en cours...</span>
        </div>
    `;

    fetch('{{ route("clients.bulk-action") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Recharger la liste
            performSearch();
            // Masquer la barre d'actions
            bulkActionsBar.style.display = 'none';
        } else {
            showToast(data.message || 'Une erreur s\'est produite', 'error');
            bulkActionsBar.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de communication', 'error');
        bulkActionsBar.innerHTML = originalContent;
    });
}

function showToast(message, type = 'info') {
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';

    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white ${bgClass} border-0 position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
});
</script>
@endpush
@endsection