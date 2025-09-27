@extends('layouts.app')

@section('title', $campaign->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item active">{{ $campaign->name }}</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <div class="d-flex align-items-center mb-2">
            <h1 class="h3 mb-0 me-3">{{ $campaign->name }}</h1>
            <x-badge variant="{{ $campaign->type === 'hajj' ? 'primary' : 'info' }}" pill>
                {{ ucfirst($campaign->type) }}
            </x-badge>
            <x-badge variant="{{
                $campaign->status === 'active' ? 'success' :
                ($campaign->status === 'open' ? 'primary' :
                ($campaign->status === 'closed' ? 'warning' : 'secondary'))
            }}" class="ms-2">
                {{
                    $campaign->status === 'draft' ? 'Brouillon' :
                    ($campaign->status === 'open' ? 'Ouverte' :
                    ($campaign->status === 'closed' ? 'Fermée' :
                    ($campaign->status === 'active' ? 'Active' : 'Terminée')))
                }}
            </x-badge>
        </div>
        <p class="text-muted mb-0">
            Du {{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }}
            au {{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}
            • {{ $campaign->year_hijri }}H / {{ $campaign->year_gregorian }}G
        </p>
    </div>
    <div>
        
        <x-button href="{{ route('campaigns.edit', $campaign) }}" variant="outline-primary" icon="fas fa-edit" class="me-2">
            Modifier
        </x-button>
        
        <x-button href="{{ route('campaigns.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="h-100 bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['total_pilgrims'] }}</div>
                    <div class="text-white-75">Pèlerins Inscrits</div>
                </div>
                <i class="fas fa-users fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="h-100 bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($stats['total_collected'], 0, ',', ' ') }}</div>
                    <div class="text-white-75">FCFA Collectés</div>
                </div>
                <i class="fas fa-money-bill fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="h-100 bg-info text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['completed_documents'] }}</div>
                    <div class="text-white-75">Dossiers Complets</div>
                </div>
                <i class="fas fa-check-circle fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="h-100 bg-warning text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['pending_documents'] }}</div>
                    <div class="text-white-75">Documents Manquants</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-12">
        <x-card title="💰 Résumé Financier" class="border-left-primary">
            <div class="row">
                <!-- Expected Amount -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="text-center">
                        <div class="h4 text-primary mb-1">{{ number_format($stats['total_expected'], 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">Montant Total Attendu</div>
                        <div class="small text-info">
                            {{ $stats['classic_pilgrims'] }} × {{ number_format($campaign->price_classic, 0, ',', ' ') }} +
                            {{ $stats['vip_pilgrims'] }} × {{ number_format($campaign->price_vip, 0, ',', ' ') }}
                        </div>
                    </div>
                </div>

                <!-- Collected Amount -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="text-center">
                        <div class="h4 text-success mb-1">{{ number_format($stats['total_collected'], 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">Montant Encaissé</div>
                        <div class="small text-success">
                            {{ $stats['collection_percentage'] }}% collecté
                        </div>
                    </div>
                </div>

                <!-- Remaining Amount -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="text-center">
                        <div class="h4 text-warning mb-1">{{ number_format($stats['remaining_amount'], 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">Reste à Encaisser</div>
                        <div class="small text-warning">
                            {{ number_format(100 - $stats['collection_percentage'], 2) }}% restant
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="text-center">
                        <div class="h4 text-info mb-1">{{ number_format($stats['pending_payments'], 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">Paiements en Attente</div>
                        <div class="small text-info">
                            En cours de traitement
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Progression des Encaissements</span>
                    <span class="badge bg-primary">{{ $stats['collection_percentage'] }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ $stats['collection_percentage'] }}%"
                         title="Encaissé: {{ number_format($stats['total_collected'], 0, ',', ' ') }} FCFA">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-muted">0 FCFA</small>
                    <small class="text-muted">{{ number_format($stats['total_expected'], 0, ',', ' ') }} FCFA</small>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Campaign Details and Actions -->
<div class="row">
    <div class="col-lg-8">
        <!-- Campaign Information -->
        <x-card title="Détails de la Campagne" class="mb-4">
            @if($campaign->description)
            <p class="mb-3">{{ $campaign->description }}</p>
            <hr>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h6>Informations Générales</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Type :</dt>
                        <dd class="col-sm-7">
                            <x-badge variant="{{ $campaign->type === 'hajj' ? 'primary' : 'info' }}">
                                {{ ucfirst($campaign->type) }}
                            </x-badge>
                        </dd>
                        <dt class="col-sm-5">Prix Classique :</dt>
                        <dd class="col-sm-7 text-success fw-bold">{{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA</dd>
                        <dt class="col-sm-5">Prix VIP :</dt>
                        <dd class="col-sm-7 text-warning fw-bold">{{ number_format($campaign->price_vip, 0, ',', ' ') }} FCFA</dd>
                        <dt class="col-sm-5">Créée le :</dt>
                        <dd class="col-sm-7">{{ $campaign->created_at->format('d/m/Y à H:i') }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h6>Répartition par Catégorie</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Pèlerins Classiques :</dt>
                        <dd class="col-sm-6">{{ $stats['classic_pilgrims'] ?? 0 }}</dd>
                        <dt class="col-sm-6">Pèlerins VIP :</dt>
                        <dd class="col-sm-6">{{ $stats['vip_pilgrims'] ?? 0 }}</dd>
                        <dt class="col-sm-6">Total inscrit :</dt>
                        <dd class="col-sm-6 fw-bold">{{ $stats['total_pilgrims'] }}</dd>
                    </dl>

                    <div class="mt-3">
                        <small class="text-muted d-block mb-1">Répartition des catégories</small>
                        @php
                            $classicCount = $stats['classic_pilgrims'] ?? 0;
                            $vipCount = $stats['vip_pilgrims'] ?? 0;
                            $total = $classicCount + $vipCount;
                            $classicPercentage = $total > 0 ? ($classicCount / $total) * 100 : 0;
                            $vipPercentage = $total > 0 ? ($vipCount / $total) * 100 : 0;
                        @endphp
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $classicPercentage }}%" title="{{ $classicCount }} Classiques"></div>
                            <div class="progress-bar bg-warning" style="width: {{ $vipPercentage }}%" title="{{ $vipCount }} VIP"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-success">{{ $classicCount }} Classiques</small>
                            <small class="text-warning">{{ $vipCount }} VIP</small>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Recent Activities -->
        <div class="row">
            <!-- Recent Pilgrims -->
            <div class="col-lg-6 mb-4">
                <x-card title="Pèlerins Récents" class="h-100">
                    @if($recentPilgrims->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPilgrims as $pilgrim)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-secondary text-white rounded-circle me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                            {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</div>
                                            <small class="text-muted">{{ $pilgrim->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <x-badge variant="outline-secondary">{{ ucfirst($pilgrim->status) }}</x-badge>
                                </div>
                            @endforeach
                        </div>
                        @if($stats['total_pilgrims'] > 5)
                        <div class="card-footer bg-transparent text-center">
                            <a href="{{ route('pilgrims.index', ['campaign' => $campaign->id]) }}" class="btn btn-sm btn-outline-primary">
                                Voir tous les pèlerins ({{ $stats['total_pilgrims'] }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">Aucun pèlerin inscrit</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Recent Payments -->
            <div class="col-lg-6 mb-4">
                <x-card title="Paiements Récents" class="h-100">
                    @if($recentPayments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPayments as $payment)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-semibold">{{ $payment->firstname }} {{ $payment->lastname }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                        <x-badge variant="outline-success">{{ ucfirst($payment->status) }}</x-badge>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <a href="{{ route('payments.index', ['campaign' => $campaign->id]) }}" class="btn btn-sm btn-outline-primary">
                                Voir tous les paiements
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">Aucun paiement enregistré</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        
        <x-card title="Actions Rapides" class="mb-4">
            <div class="d-grid gap-2">
                @if($campaign->status === 'active')
                    <form method="POST" action="{{ route('campaigns.deactivate', $campaign) }}">
                        @csrf
                        <x-button type="submit" variant="warning" icon="fas fa-pause" class="w-100">
                            Désactiver la Campagne
                        </x-button>
                    </form>
                @else
                    <form method="POST" action="{{ route('campaigns.activate', $campaign) }}">
                        @csrf
                        <x-button type="submit" variant="success" icon="fas fa-play" class="w-100">
                            Activer la Campagne
                        </x-button>
                    </form>
                @endif

                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addPilgrimModal">
                    <i class="fas fa-user-plus me-1"></i>
                    Ajouter un Pèlerin
                </button>

                @if($stats['total_pilgrims'] > 0)
                <x-button href="{{ route('reports.campaigns') }}?campaign={{ $campaign->id }}" variant="info" icon="fas fa-chart-bar" class="w-100">
                    Voir les Rapports
                </x-button>
                @endif
            </div>
        </x-card>
        

        <!-- Campaign Status -->
        <x-card title="Statut de la Campagne">
            <div class="text-center">
                @if($campaign->is_active)
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5 class="text-success">Active</h5>
                    <p class="text-muted">Cette campagne accepte de nouvelles inscriptions</p>
                @else
                    <i class="fas fa-pause-circle text-warning fa-3x mb-3"></i>
                    <h5 class="text-warning">Inactive</h5>
                    <p class="text-muted">Cette campagne n'accepte plus de nouvelles inscriptions</p>
                @endif

                @php
                    $startDate = \Carbon\Carbon::parse($campaign->departure_date);
                    $endDate = \Carbon\Carbon::parse($campaign->return_date);
                    $now = \Carbon\Carbon::now();
                @endphp

                <hr>
                <div class="text-start">
                    @if($now->isBefore($startDate))
                        <div class="d-flex justify-content-between">
                            <span>Commence dans :</span>
                            <strong class="text-primary">{{ $now->diffInDays($startDate) }} jours</strong>
                        </div>
                    @elseif($now->isBetween($startDate, $endDate))
                        <div class="d-flex justify-content-between">
                            <span>En cours :</span>
                            <strong class="text-success">{{ $now->diffInDays($endDate) }} jours restants</strong>
                        </div>
                    @else
                        <div class="d-flex justify-content-between">
                            <span>Terminée depuis :</span>
                            <strong class="text-muted">{{ $endDate->diffForHumans() }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Modal d'ajout de pèlerin -->
<div class="modal fade" id="addPilgrimModal" tabindex="-1" aria-labelledby="addPilgrimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPilgrimModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Ajouter un Pèlerin - {{ $campaign->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPilgrimForm" method="POST" action="{{ route('pilgrims.store') }}">
                    @csrf
                    <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                    <!-- Sélection du client -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">👤 Sélection du Client</h6>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label">Rechercher un client existant</label>
                                <select name="client_id" id="modalClientSelect" class="form-select">
                                    <option value="">Tapez pour rechercher un client...</option>
                                </select>
                                <div class="form-text">Recherche par nom, téléphone ou email</div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="showNewClientForm()">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Nouveau Client
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire nouveau client (caché par défaut) -->
                    <div id="newClientForm" style="display: none;">
                        <h6 class="border-bottom pb-2 mb-3">✨ Nouveau Client</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" name="new_client_firstname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" name="new_client_lastname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Genre *</label>
                                    <select class="form-select" name="new_client_gender">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="male">Homme</option>
                                        <option value="female">Femme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de naissance *</label>
                                    <input type="date" class="form-control" name="new_client_date_of_birth">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" name="new_client_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="new_client_email">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">🏷️ Catégorie</h6>
                        @if($campaign->type === 'omra')
                            <input type="hidden" name="category" value="classic">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Formule unique pour Omra : <strong>{{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" value="classic" id="classicCategory" required>
                                        <label class="form-check-label" for="classicCategory">
                                            🥉 <strong>Classique</strong><br>
                                            <small class="text-muted">{{ number_format($campaign->price_classic, 0, ',', ' ') }} FCFA</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" value="vip" id="vipCategory" required>
                                        <label class="form-check-label" for="vipCategory">
                                            🥇 <strong>VIP</strong><br>
                                            <small class="text-muted">{{ number_format($campaign->price_vip, 0, ',', ' ') }} FCFA</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="addPilgrimForm" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>
                    Inscrire le Pèlerin
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- jQuery et Select2 -->
<script>
// Check if jQuery is already loaded
if (typeof jQuery === 'undefined') {
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    script.onload = function() {
        loadSelect2();
    };
    document.head.appendChild(script);
} else {
    loadSelect2();
}

function loadSelect2() {
    if (typeof $.fn.select2 === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        script.onload = function() {
            initializeSelect2Functions();
        };
        document.head.appendChild(script);
    } else {
        initializeSelect2Functions();
    }
}

let isNewClient = false;

function showNewClientForm() {
    const newClientForm = document.getElementById('newClientForm');
    const clientSelect = document.getElementById('modalClientSelect');

    if (isNewClient) {
        // Cacher le formulaire nouveau client
        newClientForm.style.display = 'none';
        clientSelect.disabled = false;
        isNewClient = false;
        event.target.innerHTML = '<i class="fas fa-user-plus me-1"></i>Nouveau Client';
    } else {
        // Afficher le formulaire nouveau client
        newClientForm.style.display = 'block';
        clientSelect.disabled = true;
        clientSelect.value = '';
        isNewClient = true;
        event.target.innerHTML = '<i class="fas fa-search me-1"></i>Client Existant';
    }
}

function initializeSelect2Functions() {
    console.log('Initializing Select2 functions...');

    // Attendre que le DOM soit prêt
    $(document).ready(function() {
        console.log('DOM ready, setting up modal event handlers...');

        // Détacher les anciens gestionnaires d'événements pour éviter les doublons
        $('#addPilgrimModal').off('shown.bs.modal').off('hidden.bs.modal');

        // Gestionnaire d'ouverture du modal
        $('#addPilgrimModal').on('shown.bs.modal', function () {
            console.log('Modal opened, initializing Select2...');

            const $clientSelect = $('#modalClientSelect');

            // Détruire Select2 s'il existe déjà
            if ($clientSelect.hasClass("select2-hidden-accessible")) {
                console.log('Destroying existing Select2...');
                $clientSelect.select2('destroy');
            }

            // Vérifier que l'élément existe
            if ($clientSelect.length === 0) {
                console.error('Element #modalClientSelect not found!');
                return;
            }

            console.log('Initializing Select2 with AJAX...');

            // Initialiser Select2 avec AJAX
            $clientSelect.select2({
                placeholder: 'Tapez pour rechercher un client...',
                allowClear: true,
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route("clients.search") }}',
                    dataType: 'json',
                    delay: 300,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: function (params) {
                        console.log('Searching for:', params.term);
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        console.log('AJAX response received:', data);

                        if (!Array.isArray(data)) {
                            console.error('Expected array but got:', typeof data, data);
                            return { results: [] };
                        }

                        const results = data.map(function(client) {
                            return {
                                id: client.id,
                                text: client.firstname + ' ' + client.lastname + ' (' + client.phone + ')'
                            };
                        });

                        console.log('Processed results:', results);

                        return {
                            results: results,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Search Error:');
                        console.error('Status:', status);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status Code:', xhr.status);
                    }
                },
                dropdownParent: $('#addPilgrimModal'),
                width: '100%',
                theme: 'bootstrap-5'
            });

            console.log('Select2 initialized successfully');
        });

        // Gestionnaire de fermeture du modal
        $('#addPilgrimModal').on('hidden.bs.modal', function () {
            console.log('Modal closing, cleaning up...');

            const $clientSelect = $('#modalClientSelect');
            if ($clientSelect.hasClass("select2-hidden-accessible")) {
                $clientSelect.select2('destroy');
            }

            // Réinitialiser le formulaire
            const form = document.getElementById('addPilgrimForm');
            if (form) {
                form.reset();
            }

            const newClientForm = document.getElementById('newClientForm');
            if (newClientForm) {
                newClientForm.style.display = 'none';
            }

            isNewClient = false;

            console.log('Modal cleanup completed');
        });

        console.log('Event handlers set up successfully');
    });
}


// Gérer la soumission du formulaire
document.getElementById('addPilgrimForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Si c'est un nouveau client, ajouter les données
    if (isNewClient) {
        formData.append('create_new_client', '1');
    }

    // Envoyer via AJAX pour rester sur la page
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(`HTTP ${response.status}: ${JSON.stringify(errorData)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);

        if (data.success) {
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPilgrimModal'));
            modal.hide();

            // Afficher un message de succès
            if (data.message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                alertDiv.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.main-content').prepend(alertDiv);
            }

            // Recharger la page pour voir le nouveau pèlerin
            setTimeout(() => window.location.reload(), 1000);
        } else {
            alert('Erreur lors de l\'inscription du pèlerin: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);

        // Essayer d'extraire les erreurs de validation
        let errorMessage = 'Une erreur est survenue';

        try {
            const errorData = JSON.parse(error.message.split(': ')[1]);
            if (errorData.errors) {
                const validationErrors = Object.values(errorData.errors).flat();
                errorMessage = validationErrors.join('\n');
            } else if (errorData.message) {
                errorMessage = errorData.message;
            }
        } catch (parseError) {
            errorMessage = error.message;
        }

        alert(errorMessage);
    });
});
</script>
@endpush
@endsection