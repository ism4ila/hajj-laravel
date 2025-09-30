@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-responsive">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="card-responsive stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-responsive-xs text-muted mb-1">TOTAL PÈLERINS</div>
                        <div class="text-responsive-xxl fw-bold text-primary">{{ number_format($stats['total_pilgrims']) }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-responsive bg-gradient-to-br from-green-500 to-green-600 text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-responsive-xs text-light opacity-75 mb-1">CAMPAGNES ACTIVES</div>
                        <div class="text-responsive-xxl fw-bold text-white">{{ $stats['active_campaigns'] }}</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="fas fa-flag fa-lg text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-responsive bg-gradient-to-br from-blue-500 to-blue-600 text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-responsive-xs text-light opacity-75 mb-1">TOTAL PAIEMENTS</div>
                        <div class="text-responsive-lg fw-bold text-white">{{ number_format($stats['total_payments'], 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="fas fa-credit-card fa-lg text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-responsive bg-gradient-to-br from-yellow-500 to-yellow-600 text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-responsive-xs text-light opacity-75 mb-1">DOCUMENTS MANQUANTS</div>
                        <div class="text-responsive-xxl fw-bold text-white">{{ $stats['pending_documents'] }}</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="fas fa-exclamation-triangle fa-lg text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-responsive-lg">
    <div class="card-responsive">
        <div class="card-body">
            <h5 class="card-title text-responsive-lg fw-bold mb-responsive-md">
                <i class="fas fa-bolt text-primary me-2"></i>Actions Rapides
            </h5>
            <div class="grid-responsive grid-auto-fit">
                <div>
                    <a href="{{ route('pilgrims.create') }}" class="btn-responsive btn-primary text-center p-responsive-md">
                        <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                        <strong class="text-responsive-sm">Nouveau Pèlerin</strong>
                    </a>
                </div>
                <div>
                    <a href="{{ route('campaigns.create') }}" class="btn-responsive btn-success text-center p-responsive-md">
                        <i class="fas fa-flag fa-2x d-block mb-2"></i>
                        <strong class="text-responsive-sm">Nouvelle Campagne</strong>
                    </a>
                </div>
                <div>
                    <a href="{{ route('payments.create') }}" class="btn-responsive btn-info text-center p-responsive-md">
                        <i class="fas fa-credit-card fa-2x d-block mb-2"></i>
                        <strong class="text-responsive-sm">Nouveau Paiement</strong>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('reports.index') }}" class="btn btn-warning w-100 py-3">
                        <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                        <strong>Rapports</strong>
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Charts and Recent Activities Row -->
<div class="row">
    <!-- Monthly Payments Chart -->
    <div class="col-lg-8 mb-4">
        <x-card title="Évolution des Paiements (6 derniers mois)" class="h-100">
            <canvas id="paymentsChart" height="300"></canvas>
        </x-card>
    </div>

    <!-- Payment Status Distribution -->
    <div class="col-lg-4 mb-4">
        <x-card title="Statut des Paiements" class="h-100">
            <canvas id="paymentStatusChart"></canvas>
        </x-card>
    </div>
</div>

<!-- Recent Activities Row -->
<div class="row">
    <!-- Recent Pilgrims -->
    <div class="col-lg-6 mb-4">
        <x-card title="Pèlerins Récents" class="h-100">
            @if($stats['recent_pilgrims']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['recent_pilgrims'] as $pilgrim)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</h6>
                                    <small class="text-muted">{{ $pilgrim->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <x-badge variant="secondary">{{ ucfirst($pilgrim->status) }}</x-badge>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('pilgrims.index') }}" class="btn btn-outline-primary btn-sm">
                        Voir tous les pèlerins
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users text-muted fa-3x mb-3"></i>
                    <p class="text-muted">Aucun pèlerin enregistré</p>
                    <a href="{{ route('pilgrims.create') }}" class="btn btn-primary">
                        Ajouter un pèlerin
                    </a>
                </div>
            @endif
        </x-card>
    </div>

    <!-- Recent Payments -->
    <div class="col-lg-6 mb-4">
        <x-card title="Paiements Récents" class="h-100">
            @if($stats['recent_payments']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['recent_payments'] as $payment)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $payment->pilgrim->firstname ?? 'N/A' }} {{ $payment->pilgrim->lastname ?? '' }}</h6>
                                <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                <x-badge variant="{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </x-badge>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-primary btn-sm">
                        Voir tous les paiements
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-credit-card text-muted fa-3x mb-3"></i>
                    <p class="text-muted">Aucun paiement enregistré</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        Ajouter un paiement
                    </a>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Campaign Performance -->
<div class="row">
    <div class="col-12">
        <x-card title="Performance des Campagnes" class="mb-4">
            @if($campaignStats->count() > 0)
                <div class="row">
                    @foreach($campaignStats as $campaign)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $campaign->name }}</h6>
                                        <small class="text-muted">Type: {{ ucfirst($campaign->type) }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-primary">{{ $campaign->pilgrims_count }}</div>
                                        <small class="text-muted">pèlerins</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-flag text-muted fa-3x mb-3"></i>
                    <p class="text-muted">Aucune campagne active</p>
                    <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
                        Créer une campagne
                    </a>
                </div>
            @endif
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Payments Chart
const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
const paymentsChart = new Chart(paymentsCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($monthlyPayments as $payment)
                '{{ \Carbon\Carbon::parse($payment->month)->format("M Y") }}',
            @endforeach
        ],
        datasets: [{
            label: 'Montant (FCFA)',
            data: [
                @foreach($monthlyPayments as $payment)
                    {{ $payment->total }},
                @endforeach
            ],
            borderColor: 'rgb(44, 90, 160)',
            backgroundColor: 'rgba(44, 90, 160, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('fr-MA').format(value) + ' FCFA';
                    }
                }
            }
        }
    }
});

// Payment Status Chart
const statusCtx = document.getElementById('paymentStatusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($paymentStatusStats as $status => $count)
                '{{ ucfirst($status) }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($paymentStatusStats as $status => $count)
                    {{ $count }},
                @endforeach
            ],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Styles spécifiques pour le dashboard responsive */
    .stats-grid .card-responsive {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stats-grid .card-responsive:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, var(--tw-gradient-from), var(--tw-gradient-to));
    }

    .from-green-500 { --tw-gradient-from: #10b981; }
    .to-green-600 { --tw-gradient-to: #059669; }
    .from-blue-500 { --tw-gradient-from: #3b82f6; }
    .to-blue-600 { --tw-gradient-to: #2563eb; }
    .from-yellow-500 { --tw-gradient-from: #f59e0b; }
    .to-yellow-600 { --tw-gradient-to: #d97706; }

    .btn-responsive.text-center {
        flex-direction: column;
        text-decoration: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .btn-responsive.text-center:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn-responsive.btn-info {
        background: linear-gradient(135deg, #0dcaf0, #0bb5d6);
        color: white;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .text-responsive-lg {
            font-size: 1rem !important;
        }
    }
</style>
@endpush