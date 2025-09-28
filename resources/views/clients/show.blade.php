@extends('layouts.app')

@section('title', $client->full_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">{{ $client->full_name }}</li>
@endsection

@section('content')
<div class="client-profile-page">
    <!-- En-tête profil client moderne responsive -->
    <div class="client-header bg-gradient-primary text-white rounded-4 mb-4 overflow-hidden position-relative" data-aos="fade-down">
        <div class="position-absolute top-0 end-0 opacity-10 d-none d-lg-block">
            <i class="fas fa-user-circle" style="font-size: 15rem;"></i>
        </div>
        <div class="container-fluid p-responsive-lg position-relative">
            <div class="row align-items-center g-3">
                <div class="col-auto order-1">
                    <div class="position-relative">
                        <div class="avatar-responsive bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center text-white shadow-lg">
                            {{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}
                        </div>
                        @if($client->is_active)
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 status-indicator">
                                <i class="fas fa-check text-white"></i>
                            </span>
                        @else
                            <span class="position-absolute bottom-0 end-0 bg-danger rounded-circle p-2 status-indicator">
                                <i class="fas fa-times text-white"></i>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col order-2 order-lg-2">
                    <h1 class="client-name mb-2 text-white fw-bold">{{ $client->full_name }}</h1>
                    <div class="client-badges d-flex flex-wrap gap-2 mb-3">
                        <span class="badge badge-responsive bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                            <i class="fas fa-birthday-cake me-1"></i>
                            <span class="d-none d-sm-inline">{{ $client->age }} ans</span>
                            <span class="d-sm-none">{{ $client->age }}a</span>
                        </span>
                        <span class="badge badge-responsive bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                            <i class="fas fa-{{ $client->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                            <span class="d-none d-sm-inline">{{ $client->gender === 'male' ? 'Homme' : 'Femme' }}</span>
                            <span class="d-sm-none">{{ $client->gender === 'male' ? 'H' : 'F' }}</span>
                        </span>
                        @if($client->nationality)
                            <span class="badge badge-responsive bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                                <i class="fas fa-flag me-1"></i>
                                <span class="d-none d-md-inline">{{ $client->nationality }}</span>
                                <span class="d-md-none">{{ Str::limit($client->nationality, 3, '') }}</span>
                            </span>
                            @if($client->region)
                                <span class="badge badge-responsive bg-white bg-opacity-20 text-white border border-white border-opacity-30 d-none d-lg-inline-flex">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $client->region }}
                                </span>
                            @endif
                        @endif
                        @if($client->pilgrims->count() > 0)
                            <span class="badge badge-responsive bg-white bg-opacity-30 text-white border border-white border-opacity-50">
                                <i class="fas fa-route me-1"></i>
                                <span class="d-none d-sm-inline">{{ $client->pilgrims->count() }} pèlerinages</span>
                                <span class="d-sm-none">{{ $client->pilgrims->count() }}p</span>
                            </span>
                        @endif
                    </div>
                    <div class="client-info text-white text-opacity-75">
                        <i class="fas fa-calendar-plus me-2"></i>
                        <span class="d-none d-sm-inline">Client depuis {{ $client->created_at->diffForHumans() }}</span>
                        <span class="d-sm-none">{{ $client->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto order-3">
                    <div class="client-actions d-flex flex-column flex-lg-column gap-2">
                        <div class="d-flex flex-row flex-lg-column gap-2">
                            <a href="{{ route('clients.edit', $client) }}"
                               class="btn btn-warning btn-responsive">
                                <i class="fas fa-edit me-2"></i>
                                <span class="d-none d-sm-inline">Modifier</span>
                                <span class="d-sm-none">Edit</span>
                            </a>
                            <a href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}"
                               class="btn btn-success btn-responsive">
                                <i class="fas fa-plus me-2"></i>
                                <span class="d-none d-sm-inline">Nouveau Pèlerinage</span>
                                <span class="d-sm-none">Nouveau</span>
                            </a>
                        </div>
                        <div class="d-flex flex-row flex-lg-column gap-2">
                            <a href="{{ route('clients.index') }}"
                               class="btn btn-outline-light btn-responsive">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span class="d-none d-sm-inline">Retour</span>
                                <span class="d-sm-none">Back</span>
                            </a>
                            <button class="btn btn-outline-light btn-responsive" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                <span class="d-none d-sm-inline">Imprimer</span>
                                <span class="d-sm-none">Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-responsive">
        <!-- Contenu principal -->
        <div class="col-lg-8 order-1">
            <!-- Informations personnelles -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-user me-2"></i>Informations personnelles
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="responsive-grid grid-2">
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-user text-primary me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Nom complet</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->full_name }}</strong>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-{{ $client->gender === 'male' ? 'mars' : 'venus' }} text-{{ $client->gender === 'male' ? 'primary' : 'danger' }} me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Genre</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->gender === 'male' ? 'Homme' : 'Femme' }}</strong>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-birthday-cake text-warning me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Date de naissance</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->date_of_birth->format('d/m/Y') }}</strong>
                                <span class="badge bg-light text-dark ms-2">{{ $client->age }} ans</span>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-flag text-info me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Nationalité</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->nationality ?: 'Non renseignée' }}</strong>
                            </div>
                        </div>
                        @if($client->nationality === 'Cameroun' && ($client->region || $client->department))
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt text-success me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Région</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->region ?: 'Non renseignée' }}</strong>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-building text-secondary me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Département</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->department ?: 'Non renseigné' }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header bg-success text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-address-book me-2"></i>Informations de contact
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="responsive-grid grid-2">
                        <div class="contact-item d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-success me-3 info-icon"></i>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block text-responsive-xs">Téléphone</small>
                                @if($client->phone)
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark text-responsive-sm">
                                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                        </strong>
                                        <button class="btn btn-sm btn-outline-success" onclick="copyToClipboard('{{ $client->phone }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted text-responsive-sm">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                        <div class="contact-item d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary me-3 info-icon"></i>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block text-responsive-xs">Email</small>
                                @if($client->email)
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark text-responsive-sm">
                                            <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                        </strong>
                                        <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $client->email }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted text-responsive-sm">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                        @if($client->address)
                        <div class="contact-item d-flex align-items-start mb-3 col-span-2">
                            <i class="fas fa-home text-info me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Adresse</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->address }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            @if($client->emergency_contact || $client->emergency_phone)
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-warning text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-phone-alt me-2"></i>Contact d'urgence
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="responsive-grid grid-2">
                        <div class="emergency-item d-flex align-items-center mb-3">
                            <i class="fas fa-user text-warning me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Nom du contact</small>
                                <strong class="text-dark text-responsive-sm">{{ $client->emergency_contact ?: 'Non renseigné' }}</strong>
                            </div>
                        </div>
                        <div class="emergency-item d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-warning me-3 info-icon"></i>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block text-responsive-xs">Téléphone d'urgence</small>
                                @if($client->emergency_phone)
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark text-responsive-sm">
                                            <a href="tel:{{ $client->emergency_phone }}" class="text-decoration-none">{{ $client->emergency_phone }}</a>
                                        </strong>
                                        <button class="btn btn-sm btn-outline-warning" onclick="copyToClipboard('{{ $client->emergency_phone }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted text-responsive-sm">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Passeport -->
            @if($client->passport_number || $client->passport_expiry_date)
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-passport me-2"></i>Informations de passeport
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="responsive-grid grid-2">
                        <div class="passport-item d-flex align-items-center mb-3">
                            <i class="fas fa-id-card text-info me-3 info-icon"></i>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block text-responsive-xs">Numéro de passeport</small>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-dark text-responsive-sm">{{ $client->passport_number ?: 'Non renseigné' }}</strong>
                                    @if($client->passport_number)
                                        <button class="btn btn-sm btn-outline-info" onclick="copyToClipboard('{{ $client->passport_number }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="passport-item d-flex align-items-center mb-3">
                            <i class="fas fa-calendar-alt text-info me-3 info-icon"></i>
                            <div>
                                <small class="text-muted d-block text-responsive-xs">Date d'expiration</small>
                                @if($client->passport_expiry_date)
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark text-responsive-sm">{{ $client->passport_expiry_date->format('d/m/Y') }}</strong>
                                        @if($client->passport_expiry_date->isPast())
                                            <span class="badge bg-danger">Expiré</span>
                                        @elseif($client->passport_expiry_date->diffInMonths(now()) < 6)
                                            <span class="badge bg-warning">Expire bientôt</span>
                                        @else
                                            <span class="badge bg-success">Valide</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted text-responsive-sm">Non renseignée</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($client->notes)
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header bg-secondary text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-sticky-note me-2"></i>Notes
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-quote-left text-secondary me-3 mt-1"></i>
                        <p class="text-dark mb-0 text-responsive-sm" style="white-space: pre-line;">{{ $client->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 order-2 order-lg-2">
            <!-- Statistiques -->
            <div class="stats-card card border-0 shadow-sm mb-4" data-aos="fade-left">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body p-responsive-md">
                    <div class="stats-grid">
                        <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="fas fa-user-check text-primary me-3 stat-icon"></i>
                                <div class="stat-label">
                                    <span class="text-muted d-block text-responsive-sm">Pèlerinages totaux</span>
                                    <small class="text-muted d-none d-md-block text-responsive-xs">Nombre total de voyages</small>
                                </div>
                            </div>
                            <span class="stat-value text-primary fw-bold" data-counter="{{ $client->total_pilgrimages ?? $client->pilgrims->count() }}">0</span>
                        </div>
                        <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="fas fa-check-circle text-success me-3 stat-icon"></i>
                                <div class="stat-label">
                                    <span class="text-muted d-block text-responsive-sm">Pèlerinages terminés</span>
                                    <small class="text-muted d-none d-md-block text-responsive-xs">Voyages accomplis</small>
                                </div>
                            </div>
                            <span class="stat-value text-success fw-bold" data-counter="{{ $client->completed_pilgrimages ?? $client->pilgrims->where('status', 'completed')->count() }}">0</span>
                        </div>
                        <div class="stat-item d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="fas fa-money-bill-wave text-warning me-3 stat-icon"></i>
                                <div class="stat-label">
                                    <span class="text-muted d-block text-responsive-sm">Total dépensé</span>
                                    <small class="text-muted d-none d-md-block text-responsive-xs">Montant total payé</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="stat-value text-warning fw-bold d-block">{{ number_format($client->total_spent ?? $client->pilgrims->sum('paid_amount'), 0, ',', ' ') }}</span>
                                <small class="text-muted text-responsive-xs">FCFA</small>
                            </div>
                        </div>
                        <div class="stat-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="fas fa-circle text-{{ $client->is_active ? 'success' : 'danger' }} me-3 stat-icon"></i>
                                <div class="stat-label">
                                    <span class="text-muted d-block text-responsive-sm">Statut</span>
                                    <small class="text-muted d-none d-md-block text-responsive-xs">État du compte</small>
                                </div>
                            </div>
                            <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }} px-3 py-2 text-responsive-sm">
                                <i class="fas fa-{{ $client->is_active ? 'check' : 'times' }} me-1"></i>
                                {{ $client->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions-card card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="100">
                <div class="card-header bg-dark text-white border-0">
                    <h5 class="card-title mb-0 text-responsive-lg">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body p-responsive-sm">
                    <div class="d-grid gap-responsive">
                        <a href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}"
                           class="btn btn-success btn-responsive-lg d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i>
                            <span class="d-none d-sm-inline">Nouveau pèlerinage</span>
                            <span class="d-sm-none">Nouveau</span>
                        </a>
                        <a href="{{ route('clients.edit', $client) }}"
                           class="btn btn-warning btn-responsive-lg d-flex align-items-center justify-content-center">
                            <i class="fas fa-edit me-2"></i>
                            <span class="d-none d-sm-inline">Modifier le client</span>
                            <span class="d-sm-none">Modifier</span>
                        </a>
                        <button class="btn btn-outline-danger btn-responsive-lg d-flex align-items-center justify-content-center"
                                onclick="toggleClientStatus({{ $client->id }}, {{ $client->is_active ? 'false' : 'true' }})">
                            <i class="fas fa-{{ $client->is_active ? 'pause' : 'play' }} me-2"></i>
                            <span class="d-none d-sm-inline">{{ $client->is_active ? 'Désactiver' : 'Activer' }}</span>
                            <span class="d-sm-none">{{ $client->is_active ? 'Off' : 'On' }}</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-responsive-lg dropdown-toggle d-flex align-items-center justify-content-center"
                                    type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-h me-2"></i>
                                <span class="d-none d-sm-inline">Plus d'actions</span>
                                <span class="d-sm-none">Plus</span>
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#" onclick="exportClientData({{ $client->id }})">
                                    <i class="fas fa-download me-2 text-info"></i>Exporter les données
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="sendEmail({{ $client->id }})">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Envoyer un email
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="archiveClient({{ $client->id }})">
                                    <i class="fas fa-archive me-2"></i>Archiver le client
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des pèlerinages -->
        @if($client->pilgrims->count() > 0)
        <div class="col-12 order-3">
            <div class="pilgrimages-card card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="card-title mb-0 text-responsive-lg">
                            <i class="fas fa-history me-2"></i>Historique des pèlerinages
                            <span class="badge bg-white text-primary ms-2">{{ $client->pilgrims->count() }}</span>
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-light btn-sm" onclick="toggleTableView()">
                                <i class="fas fa-th-list me-1"></i>
                                <span class="d-none d-md-inline">Vue liste</span>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="exportPilgrimages()">
                                <i class="fas fa-download me-1"></i>
                                <span class="d-none d-md-inline">Exporter</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Vue Tableau -->
                    <div class="table-view" id="tableView">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-responsive border-0">Campagne</th>
                                        <th class="border-0 d-none d-md-table-cell">Catégorie</th>
                                        <th class="border-0">Statut</th>
                                        <th class="border-0 d-none d-lg-table-cell">Paiements</th>
                                        <th class="text-center border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->pilgrims as $pilgrim)
                                    <tr class="table-row-hover">
                                        <td class="ps-responsive">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-kaaba text-primary me-3 d-none d-sm-inline"></i>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold text-dark text-responsive-sm">
                                                        {{ $pilgrim->campaign ? $pilgrim->campaign->name : 'Campagne supprimée' }}
                                                    </div>
                                                    <small class="text-muted d-block text-responsive-xs">
                                                        @if($pilgrim->campaign && $pilgrim->campaign->departure_date)
                                                            {{ $pilgrim->campaign->departure_date->format('Y') }} - {{ $pilgrim->campaign->type }}
                                                        @else
                                                            -
                                                        @endif
                                                    </small>
                                                    <!-- Mobile: Catégorie affichée ici -->
                                                    <div class="d-md-none mt-1">
                                                        <span class="badge bg-{{ $pilgrim->category === 'vip' ? 'purple' : 'primary' }} badge-sm">
                                                            <i class="fas fa-{{ $pilgrim->category === 'vip' ? 'crown' : 'user' }} me-1"></i>
                                                            {{ ucfirst($pilgrim->category) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-{{ $pilgrim->category === 'vip' ? 'purple' : 'primary' }} px-3 py-2">
                                                <i class="fas fa-{{ $pilgrim->category === 'vip' ? 'crown' : 'user' }} me-1"></i>
                                                {{ ucfirst($pilgrim->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $pilgrim->status === 'paid' ? 'success' : ($pilgrim->status === 'cancelled' ? 'danger' : 'warning') }} badge-responsive">
                                                <i class="fas fa-{{ $pilgrim->status === 'paid' ? 'check-circle' : ($pilgrim->status === 'cancelled' ? 'times-circle' : 'clock') }} me-1"></i>
                                                <span class="d-none d-sm-inline">{{ ucfirst($pilgrim->status) }}</span>
                                                <span class="d-sm-none">{{ substr(ucfirst($pilgrim->status), 0, 1) }}</span>
                                            </span>
                                            <!-- Mobile: Paiements affichés ici -->
                                            <div class="d-lg-none mt-1">
                                                <div class="text-responsive-xs">
                                                    <span class="fw-bold text-success">{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }}</span>
                                                    <span class="text-muted">/ {{ number_format($pilgrim->total_amount, 0, ',', ' ') }}</span>
                                                </div>
                                                @php
                                                    $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                                                @endphp
                                                <div class="progress mt-1" style="height: 3px;">
                                                    <div class="progress-bar bg-{{ $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <div>
                                                <span class="fw-bold text-success">{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} FCFA</span>
                                                <div class="text-muted small">sur {{ number_format($pilgrim->total_amount, 0, ',', ' ') }} FCFA</div>
                                                @php
                                                    $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                                                @endphp
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-{{ $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-responsive">
                                                <a href="{{ route('pilgrims.show', $pilgrim) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-xl-inline ms-1">Détails</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* RESPONSIVE STYLES POUR VUE CLIENT SHOW */
    .client-profile-page {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        min-height: calc(100vh - 120px);
    }

    /* Header responsive */
    .client-header {
        border-radius: 1rem !important;
        overflow: hidden;
        transition: all var(--transition-normal);
    }

    .client-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Avatar responsive */
    .avatar-responsive {
        width: 80px;
        height: 80px;
        font-size: 2rem;
        font-weight: 700;
        transition: all var(--transition-normal);
    }

    @media (min-width: 768px) {
        .avatar-responsive {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }
    }

    .status-indicator {
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Client info responsive */
    .client-name {
        font-size: clamp(1.5rem, 4vw, 2.5rem);
        line-height: 1.2;
    }

    .badge-responsive {
        font-size: clamp(0.7rem, 1.5vw, 0.85rem);
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25em;
    }

    /* Actions responsive */
    .btn-responsive {
        min-height: 44px;
        padding: var(--spacing-xs) var(--spacing-md);
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
        text-align: center;
        justify-content: center;
    }

    .btn-responsive:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-responsive-lg {
        min-height: 48px;
        padding: var(--spacing-md) var(--spacing-lg);
        font-size: var(--font-size-sm);
    }

    @media (max-width: 576px) {
        .client-actions .d-flex {
            width: 100%;
        }

        .btn-responsive {
            flex: 1;
        }
    }

    /* Grid responsive */
    .g-responsive {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1.5rem;
    }

    @media (max-width: 768px) {
        .g-responsive {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
    }

    .responsive-grid.grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }

    @media (max-width: 768px) {
        .responsive-grid.grid-2 {
            grid-template-columns: 1fr;
            gap: var(--spacing-sm);
        }
    }

    .col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .col-span-2 {
            grid-column: span 1;
        }
    }

    /* Cards responsive */
    .card {
        border-radius: 0.75rem;
        transition: all var(--transition-normal);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Info items */
    .info-item, .contact-item, .emergency-item, .passport-item {
        background: rgba(248, 249, 250, 0.3);
        padding: var(--spacing-sm);
        border-radius: 0.5rem;
        transition: all var(--transition-fast);
    }

    .info-item:hover, .contact-item:hover, .emergency-item:hover, .passport-item:hover {
        background: rgba(248, 249, 250, 0.6);
        transform: translateX(3px);
    }

    .info-icon, .stat-icon {
        width: 20px;
        text-align: center;
        font-size: 1.1em;
    }

    /* Stats responsive */
    .stats-grid {
        display: grid;
        gap: var(--spacing-sm);
    }

    .stat-item {
        background: rgba(248, 249, 250, 0.3);
        padding: var(--spacing-sm);
        border-radius: 0.5rem;
        transition: all var(--transition-fast);
    }

    .stat-item:hover {
        background: rgba(248, 249, 250, 0.6);
        transform: translateY(-2px);
    }

    .stat-value {
        font-size: clamp(1.2rem, 3vw, 1.5rem);
    }

    /* Actions rapides */
    .gap-responsive {
        gap: var(--spacing-sm);
    }

    @media (max-width: 576px) {
        .gap-responsive {
            gap: var(--spacing-xs);
        }
    }

    /* Table responsive améliorée */
    .table-responsive {
        border-radius: 0.75rem;
        -webkit-overflow-scrolling: touch;
    }

    .table-row-hover {
        transition: all var(--transition-fast);
    }

    .table-row-hover:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateY(-1px);
    }

    .ps-responsive {
        padding-left: var(--spacing-md);
    }

    @media (max-width: 768px) {
        .ps-responsive {
            padding-left: var(--spacing-sm);
        }

        .table th,
        .table td {
            padding: var(--spacing-xs);
            font-size: var(--font-size-sm);
        }
    }

    /* Badge responsive */
    .badge-sm {
        font-size: 0.7rem;
        padding: 0.25em 0.5em;
    }

    /* Progress bar */
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Purple color for VIP */
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    /* Padding responsive */
    .p-responsive-sm {
        padding: var(--spacing-sm);
    }

    .p-responsive-md {
        padding: var(--spacing-md);
    }

    .p-responsive-lg {
        padding: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .p-responsive-lg {
            padding: var(--spacing-md);
        }

        .p-responsive-md {
            padding: var(--spacing-sm);
        }
    }

    @media (max-width: 576px) {
        .p-responsive-md {
            padding: var(--spacing-xs);
        }

        .p-responsive-sm {
            padding: calc(var(--spacing-xs) * 0.75);
        }
    }

    /* Typography responsive */
    .text-responsive-xs { font-size: var(--font-size-xs) !important; }
    .text-responsive-sm { font-size: var(--font-size-sm) !important; }
    .text-responsive-lg { font-size: var(--font-size-lg) !important; }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Print styles */
    @media print {
        .client-actions,
        .quick-actions-card,
        .btn,
        .dropdown {
            display: none !important;
        }

        .card {
            box-shadow: none;
            border: 1px solid #ddd;
            break-inside: avoid;
        }

        .client-header {
            background: #f8f9fa !important;
            color: #000 !important;
        }

        .badge {
            border: 1px solid currentColor;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .card {
            border: 2px solid #000;
        }

        .btn-responsive {
            border-width: 2px;
        }

        .badge {
            border: 1px solid currentColor;
        }
    }

    /* Touch improvements */
    @media (pointer: coarse) {
        .btn {
            min-height: 44px;
            min-width: 44px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        offset: 50
    });

    // Animated counters
    function animateCounter(element) {
        const target = parseInt(element.dataset.counter);
        const duration = 1500;
        const steps = 50;
        const increment = target / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            element.textContent = Math.floor(current);

            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            }
        }, duration / steps);
    }

    // Trigger counter animations when in view
    const counters = document.querySelectorAll('[data-counter]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));

    // Copy to clipboard function
    window.copyToClipboard = function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Copié dans le presse-papier!', 'success');
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    };

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('Copié dans le presse-papier!', 'success');
        } catch (err) {
            showToast('Erreur lors de la copie', 'error');
        }
        document.body.removeChild(textArea);
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();

        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const iconClass = type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info';

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white ${bgClass} border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${iconClass} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toastContainer.removeChild(toast);
        });
    }

    // Créer le conteneur de toast
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // Toggle client status
    window.toggleClientStatus = function(clientId, isActive) {
        const action = isActive === 'true' ? 'activer' : 'désactiver';
        if (confirm(`Êtes-vous sûr de vouloir ${action} ce client ?`)) {
            fetch(`/clients/${clientId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_active: isActive === 'true' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Une erreur s\'est produite', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur de communication', 'error');
            });
        }
    };

    // Export client data
    window.exportClientData = function(clientId) {
        const format = prompt('Format d\'export (excel, pdf, csv):', 'excel');
        if (format && ['excel', 'pdf', 'csv'].includes(format.toLowerCase())) {
            const url = `/clients/${clientId}/export?format=${format}`;
            window.open(url, '_blank');
            showToast('Export en cours...', 'info');
        }
    };

    // Send email
    window.sendEmail = function(clientId) {
        // Cette fonction pourrait ouvrir une modal de composition d'email
        showToast('Fonction d\'email en développement', 'info');
    };

    // Archive client
    window.archiveClient = function(clientId) {
        if (confirm('⚠️ Êtes-vous sûr de vouloir archiver ce client ?\n\nLe client sera masqué des listes principales mais ses données seront conservées.')) {
            fetch(`/clients/${clientId}/archive`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.href = '/clients', 2000);
                } else {
                    showToast(data.message || 'Une erreur s\'est produite', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur de communication', 'error');
            });
        }
    };

    // Toggle table view
    window.toggleTableView = function() {
        // Cette fonction pourrait basculer entre vue tableau et vue cartes
        showToast('Fonction de basculement de vue en développement', 'info');
    };

    // Export pilgrimages
    window.exportPilgrimages = function() {
        const format = prompt('Format d\'export (excel, pdf, csv):', 'excel');
        if (format && ['excel', 'pdf', 'csv'].includes(format.toLowerCase())) {
            const clientId = {{ $client->id }};
            const url = `/clients/${clientId}/pilgrimages/export?format=${format}`;
            window.open(url, '_blank');
            showToast('Export des pèlerinages en cours...', 'info');
        }
    };

    // Smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-refresh stats every 30 seconds (optionnel)
    setInterval(() => {
        // Ici on pourrait rafraîchir les statistiques via AJAX
        console.log('Auto-refresh stats (à implémenter)');
    }, 30000);
});
</script>
@endpush

@endsection