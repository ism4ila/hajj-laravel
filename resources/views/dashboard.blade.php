@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Tableau de Bord</h1>
        <p class="text-muted mb-0">Bienvenue dans votre système de gestion Hajj & Omra</p>
    </div>
    <div class="text-end">
        <div class="small text-muted">{{ now()->format('l d F Y') }}</div>
        <div class="fw-semibold">{{ now()->format('H:i') }}</div>
    </div>
</div>

<!-- Payment Alerts for Incomplete Payments -->
@if(isset($incompletePayments) && $incompletePayments->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                Paiements Incomplets ({{ $incompletePayments->count() }})
            </h5>
            @foreach($incompletePayments->take(3) as $pilgrim)
                <x-payment-alert :pilgrim="$pilgrim" type="warning" dismissible="true" class="mb-2" />
            @endforeach

            @if($incompletePayments->count() > 3)
                <div class="text-center">
                    <a href="{{ route('payments.index', ['status' => 'incomplete']) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-list me-1"></i>Voir tous les paiements incomplets ({{ $incompletePayments->count() - 3 }} restants)
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-primary text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['total_pilgrims'] ?? 0 }}</div>
                    <div class="text-white-75">Pèlerins Inscrits</div>
                </div>
                <i class="fas fa-users fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-success text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($stats['total_payments'] ?? 0, 0, ',', ' ') }} DH</div>
                    <div class="text-white-75">Total Encaissé</div>
                </div>
                <i class="fas fa-money-bill-wave fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-warning text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['active_campaigns'] ?? 0 }}</div>
                    <div class="text-white-75">Campagnes Actives</div>
                </div>
                <i class="fas fa-flag fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-info text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($stats['pending_payments'] ?? 0, 0, ',', ' ') }} DH</div>
                    <div class="text-white-75">En Attente</div>
                </div>
                <i class="fas fa-clock fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <x-card title="⚡ Actions Rapides" class="mb-4">
            <div class="row">
                @can('manage-pilgrims')
                <div class="col-md-3 mb-3">
                    <x-button href="{{ route('pilgrims.create') }}" variant="outline-primary" class="w-100">
                        <i class="fas fa-user-plus me-2"></i>Nouveau Pèlerin
                    </x-button>
                </div>
                @endcan

                @can('manage-payments')
                <div class="col-md-3 mb-3">
                    <x-button href="{{ route('payments.create') }}" variant="outline-success" class="w-100">
                        <i class="fas fa-plus me-2"></i>Nouveau Paiement
                    </x-button>
                </div>
                @endcan

                @can('manage-campaigns')
                <div class="col-md-3 mb-3">
                    <x-button href="{{ route('campaigns.create') }}" variant="outline-warning" class="w-100">
                        <i class="fas fa-flag me-2"></i>Nouvelle Campagne
                    </x-button>
                </div>
                @endcan

                @can('view-reports')
                <div class="col-md-3 mb-3">
                    <x-button href="{{ route('reports.index') }}" variant="outline-info" class="w-100">
                        <i class="fas fa-chart-bar me-2"></i>Rapports
                    </x-button>
                </div>
                @endcan
            </div>
        </x-card>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8">
        <!-- Recent Pilgrims -->
        <x-card title="👥 Pèlerins Récents" class="mb-4">
            @if(isset($recentPilgrims) && $recentPilgrims->count() > 0)
                <x-table.table
                    :headers="[
                        ['label' => 'Nom', 'width' => '40%'],
                        ['label' => 'Campagne', 'width' => '30%'],
                        ['label' => 'Statut', 'width' => '15%'],
                        ['label' => 'Actions', 'width' => '15%']
                    ]"
                    responsive>
                    @foreach($recentPilgrims as $pilgrim)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                        {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</div>
                                        <small class="text-muted">{{ $pilgrim->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="small">{{ $pilgrim->campaign?->name ?? 'Aucune' }}</span>
                            </td>
                            <td>
                                <x-badge variant="{{ $pilgrim->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pilgrim->status) }}
                                </x-badge>
                            </td>
                            <td>
                                <a href="{{ route('pilgrims.show', $pilgrim) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-table.table>
                <div class="text-center">
                    <a href="{{ route('pilgrims.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-1"></i>Voir tous les pèlerins
                    </a>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-users fa-2x mb-3"></i>
                    <p>Aucun pèlerin inscrit</p>
                    @can('manage-pilgrims')
                    <x-button href="{{ route('pilgrims.create') }}" variant="outline-primary" size="sm">
                        Inscrire le premier pèlerin
                    </x-button>
                    @endcan
                </div>
            @endif
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Recent Payments -->
        <x-card title="💰 Paiements Récents" class="mb-4">
            @if(isset($recentPayments) && $recentPayments->count() > 0)
                @foreach($recentPayments as $payment)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            <i class="fas fa-{{
                                $payment->payment_method === 'cash' ? 'money-bill text-success' :
                                ($payment->payment_method === 'check' ? 'money-check text-info' :
                                ($payment->payment_method === 'bank_transfer' ? 'university text-primary' : 'credit-card text-warning'))
                            }} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ number_format($payment->amount, 0, ',', ' ') }} DH</div>
                            <small class="text-muted">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</small>
                            <div class="small text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <x-badge variant="{{ $payment->status === 'completed' ? 'success' : 'warning' }}" size="sm">
                            {{ $payment->status === 'completed' ? 'OK' : 'En attente' }}
                        </x-badge>
                    </div>
                @endforeach
                <div class="text-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-1"></i>Voir tous les paiements
                    </a>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-credit-card fa-2x mb-3"></i>
                    <p>Aucun paiement enregistré</p>
                    @can('manage-payments')
                    <x-button href="{{ route('payments.create') }}" variant="outline-primary" size="sm">
                        Premier paiement
                    </x-button>
                    @endcan
                </div>
            @endif
        </x-card>

        <!-- System Status -->
        <x-card title="📊 État du Système" class="mb-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">Espace de stockage</span>
                    <span class="small text-success">OK</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 25%"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">Base de données</span>
                    <span class="small text-success">Connectée</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">Sauvegardes</span>
                    <span class="small text-warning">Recommandée</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                </div>
            </div>

            <div class="text-center mt-3">
                @can('manage-settings')
                <x-button href="{{ route('settings.index') }}" variant="outline-secondary" size="sm">
                    <i class="fas fa-cog me-1"></i>Paramètres
                </x-button>
                @endcan
            </div>
        </x-card>
    </div>
</div>
@endsection