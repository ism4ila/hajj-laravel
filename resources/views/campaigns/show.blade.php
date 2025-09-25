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
        @can('edit-campaign', $campaign)
        <x-button href="{{ route('campaigns.edit', $campaign) }}" variant="outline-primary" icon="fas fa-edit" class="me-2">
            Modifier
        </x-button>
        @endcan
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
                    <div class="h4 mb-0">{{ number_format($stats['total_payments'], 0, ',', ' ') }}</div>
                    <div class="text-white-75">DH Collectés</div>
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
                        <dd class="col-sm-7 text-success fw-bold">{{ number_format($campaign->price_classic, 0, ',', ' ') }} DH</dd>
                        <dt class="col-sm-5">Prix VIP :</dt>
                        <dd class="col-sm-7 text-warning fw-bold">{{ number_format($campaign->price_vip, 0, ',', ' ') }} DH</dd>
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
                                            {{ substr($pilgrim->first_name, 0, 1) }}{{ substr($pilgrim->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $pilgrim->first_name }} {{ $pilgrim->last_name }}</div>
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
                                        <div class="fw-semibold">{{ $payment->first_name }} {{ $payment->last_name }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ number_format($payment->amount, 0, ',', ' ') }} DH</div>
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
        @can('manage-campaigns')
        <x-card title="Actions Rapides" class="mb-4">
            <div class="d-grid gap-2">
                @if($campaign->is_active)
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

                <x-button href="{{ route('pilgrims.create', ['campaign' => $campaign->id]) }}" variant="primary" icon="fas fa-user-plus" class="w-100">
                    Ajouter un Pèlerin
                </x-button>

                @if($stats['total_pilgrims'] > 0)
                <x-button href="{{ route('reports.campaigns') }}?campaign={{ $campaign->id }}" variant="info" icon="fas fa-chart-bar" class="w-100">
                    Voir les Rapports
                </x-button>
                @endif
            </div>
        </x-card>
        @endcan

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
                    $startDate = \Carbon\Carbon::parse($campaign->start_date);
                    $endDate = \Carbon\Carbon::parse($campaign->end_date);
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
@endsection