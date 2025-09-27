@extends('layouts.app')

@section('title', 'Rapport des Pèlerins')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Pèlerins</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Rapport des Pèlerins</h1>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="pilgrims">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="pilgrims">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-primary mb-1">{{ $pilgrimStats['total_pilgrims'] }}</h3>
                    <p class="text-muted mb-0">Total Pèlerins</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-success mb-1">{{ $pilgrimStats['payment_status']['fully_paid'] }}</h3>
                    <p class="text-muted mb-0">Entièrement Payés</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-warning mb-1">{{ $pilgrimStats['payment_status']['partially_paid'] }}</h3>
                    <p class="text-muted mb-0">Partiellement Payés</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-danger mb-1">{{ $pilgrimStats['payment_status']['unpaid'] }}</h3>
                    <p class="text-muted mb-0">Non Payés</p>
                </div></div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="col-lg-4 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Répartition par Catégorie</h5></div><div class="card-body">
            <canvas id="categoryChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Répartition par Statut</h5></div><div class="card-body">
            <canvas id="statusChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statut de Paiement</h5></div><div class="card-body">
            <canvas id="paymentStatusChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    {{-- Category Statistics --}}
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques par Catégorie</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th>Nombre</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pilgrimStats['by_category'] as $category)
                        <tr>
                            <td>
                                <x-badge class="btn btn-primary">
                                    {{ ucfirst($category->category ?? 'Standard') }}
                                </x-badge>
                            </td>
                            <td>{{ $category->count }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-primary"
                                                 style="width: {{ ($category->count / max($pilgrimStats['total_pilgrims'], 1)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <small>{{ number_format(($category->count / max($pilgrimStats['total_pilgrims'], 1)) * 100, 1) }}%</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>

    {{-- Campaign Distribution --}}
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Distribution par Campagne</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Campagne</th>
                            <th>Pèlerins</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pilgrimStats['by_campaign'] as $campaign)
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $campaign->campaign->name ?? 'Sans Campagne' }}</h6>
                                    <small class="text-muted">{{ $campaign->campaign->type ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>{{ $campaign->count }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-info"
                                                 style="width: {{ ($campaign->count / max($pilgrimStats['total_pilgrims'], 1)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <small>{{ number_format(($campaign->count / max($pilgrimStats['total_pilgrims'], 1)) * 100, 1) }}%</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>

    {{-- Payment Status Details --}}
    <div class="col-12">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Analyse Détaillée des Paiements</h5></div><div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="text-center p-4 border rounded bg-success-subtle">
                        <div class="display-6 text-success mb-2">
                            {{ number_format(($pilgrimStats['payment_status']['fully_paid'] / max($pilgrimStats['total_pilgrims'], 1)) * 100, 1) }}%
                        </div>
                        <h5 class="text-success mb-2">Entièrement Payés</h5>
                        <p class="text-muted mb-0">{{ $pilgrimStats['payment_status']['fully_paid'] }} pèlerins ont soldé leur compte</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="text-center p-4 border rounded bg-warning-subtle">
                        <div class="display-6 text-warning mb-2">
                            {{ number_format(($pilgrimStats['payment_status']['partially_paid'] / max($pilgrimStats['total_pilgrims'], 1)) * 100, 1) }}%
                        </div>
                        <h5 class="text-warning mb-2">Partiellement Payés</h5>
                        <p class="text-muted mb-0">{{ $pilgrimStats['payment_status']['partially_paid'] }} pèlerins ont des paiements en cours</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="text-center p-4 border rounded bg-danger-subtle">
                        <div class="display-6 text-danger mb-2">
                            {{ number_format(($pilgrimStats['payment_status']['unpaid'] / max($pilgrimStats['total_pilgrims'], 1)) * 100, 1) }}%
                        </div>
                        <h5 class="text-danger mb-2">Non Payés</h5>
                        <p class="text-muted mb-0">{{ $pilgrimStats['payment_status']['unpaid'] }} pèlerins n'ont effectué aucun paiement</p>
                    </div>
                </div>
            </div>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = @json($pilgrimStats['by_category']);

const categoryChart = new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: categoryData.map(item => item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'Standard'),
        datasets: [{
            data: categoryData.map(item => item.count),
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d',
                '#17a2b8'
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

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = @json($pilgrimStats['by_status']);

const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusData.map(item => item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Actif'),
        datasets: [{
            data: statusData.map(item => item.count),
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

// Payment Status Chart
const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
const paymentStatusData = @json($pilgrimStats['payment_status']);

const paymentStatusChart = new Chart(paymentStatusCtx, {
    type: 'bar',
    data: {
        labels: ['Entièrement Payés', 'Partiellement Payés', 'Non Payés'],
        datasets: [{
            label: 'Nombre de Pèlerins',
            data: [paymentStatusData.fully_paid, paymentStatusData.partially_paid, paymentStatusData.unpaid],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush