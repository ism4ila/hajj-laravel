@extends('layouts.app')

@section('title', 'Gestion des Campagnes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Campagnes</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Campagnes</h1>
        <p class="text-muted mb-0">Gérez vos campagnes Hajj et Omra</p>
    </div>
    <div>
        <x-button href="{{ route('campaigns.create') }}" variant="primary" icon="fas fa-plus">
            Nouvelle Campagne
        </x-button>
    </div>
</div>

<!-- Filters -->
<x-card class="mb-4">
    <form method="GET" action="{{ route('campaigns.index') }}" class="row g-3">
        <div class="col-md-4">
            <x-form.input
                name="search"
                placeholder="Rechercher par nom..."
                :value="request('search')"
                prepend="search"
            />
        </div>
        <div class="col-md-3">
            <x-form.select
                name="type"
                :options="[
                    '' => 'Tous les types',
                    'hajj' => 'Hajj',
                    'omra' => 'Omra'
                ]"
                :value="request('type')"
            />
        </div>
        <div class="col-md-3">
            <x-form.select
                name="status"
                :options="[
                    '' => 'Tous les statuts',
                    'active' => 'Actives',
                    'inactive' => 'Inactives'
                ]"
                :value="request('status')"
            />
        </div>
        <div class="col-md-2">
            <div class="d-grid gap-2 d-md-flex">
                <x-button type="submit" variant="outline-primary" class="flex-fill">
                    Filtrer
                </x-button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-card>

<!-- Campaigns List -->
@if($campaigns->count() > 0)
    <div class="row">
        @foreach($campaigns as $campaign)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ $campaign->name }}</h6>
                            <small class="text-muted">
                                <x-badge variant="{{ $campaign->type === 'hajj' ? 'primary' : 'info' }}" pill>
                                    {{ ucfirst($campaign->type) }}
                                </x-badge>
                            </small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('campaigns.show', $campaign) }}">
                                        <i class="fas fa-eye me-2"></i>Voir détails
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('campaigns.edit', $campaign) }}">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                </li>
                                
                                
                                <li><hr class="dropdown-divider"></li>
                                @if($campaign->status === 'active')
                                    <li>
                                        <form method="POST" action="{{ route('campaigns.deactivate', $campaign) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning">
                                                <i class="fas fa-pause me-2"></i>Désactiver
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <form method="POST" action="{{ route('campaigns.activate', $campaign) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="fas fa-play me-2"></i>Activer
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                
                                
                                @if($campaign->pilgrims_count === 0)
                                <li>
                                    <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                                @endif
                                
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Statut</span>
                                <x-badge variant="{{ $campaign->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $campaign->status === 'active' ? 'Active' : ucfirst($campaign->status) }}
                                </x-badge>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Période</span>
                                <small class="text-muted">
                                    @if($campaign->departure_date)
                                        {{ $campaign->departure_date->format('d/m/Y') }}
                                    @else
                                        Départ non défini
                                    @endif
                                    -
                                    @if($campaign->return_date)
                                        {{ $campaign->return_date->format('d/m/Y') }}
                                    @else
                                        Retour non défini
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Prix</span>
                                <strong class="text-primary">{{ number_format($campaign->price, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="h4 mb-0 text-primary">{{ $campaign->pilgrims_count }}</div>
                                    <small class="text-muted">Pèlerins</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h4 mb-0 text-success">
                                    @if($campaign->max_pilgrims)
                                        {{ $campaign->max_pilgrims - $campaign->pilgrims_count }}
                                    @else
                                        ∞
                                    @endif
                                </div>
                                <small class="text-muted">Places restantes</small>
                            </div>
                        </div>

                        @if($campaign->max_pilgrims)
                            <div class="mt-3">
                                @php
                                    $percentage = $campaign->max_pilgrims > 0 ? ($campaign->pilgrims_count / $campaign->max_pilgrims) * 100 : 0;
                                    $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $progressClass }}"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ round($percentage, 1) }}% de remplissage</small>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Créée {{ \Carbon\Carbon::parse($campaign->created_at)->diffForHumans() }}
                            </small>
                            <div>
                                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $campaigns->links() }}
    </div>
@else
    <!-- Empty State -->
    <x-card class="text-center py-5">
        <i class="fas fa-flag text-muted fa-3x mb-3"></i>
        <h4>Aucune campagne trouvée</h4>
        <p class="text-muted mb-4">
            @if(request()->hasAny(['search', 'type', 'status']))
                Aucune campagne ne correspond à vos critères de recherche.
                <br>
                <a href="{{ route('campaigns.index') }}" class="btn btn-link">Voir toutes les campagnes</a>
            @else
                Commencez par créer votre première campagne.
            @endif
        </p>
        
        @if(!request()->hasAny(['search', 'type', 'status']))
            <x-button href="{{ route('campaigns.create') }}" variant="primary" icon="fas fa-plus">
                Créer une campagne
            </x-button>
        @endif
        
    </x-card>
@endif
@endsection