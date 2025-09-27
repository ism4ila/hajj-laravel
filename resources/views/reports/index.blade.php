@extends('layouts.app')

@section('title', 'Tableaux de Bord et Rapports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Rapports</li>
@endsection

@section('content')
<div class="row">
    {{-- Statistics Overview --}}
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-gradient rounded-circle p-3">
                                    <i class="fas fa-flag text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['total_campaigns'] }}</h4>
                                <p class="text-muted mb-1">Campagnes Totales</p>
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>{{ $stats['active_campaigns'] }} actives
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-gradient rounded-circle p-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['total_pilgrims'] }}</h4>
                            <p class="text-muted mb-1">Pèlerins Inscrits</p>
                            <small class="text-info">
                                <i class="fas fa-chart-line me-1"></i>{{ $stats['pilgrims_with_clients'] ?? 0 }} avec clients
                            </small>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning bg-gradient rounded-circle p-3">
                                <i class="fas fa-coins text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['total_payments'], 0, ',', ' ') }}</h4>
                            <p class="text-muted mb-1">Revenus Totaux (FCFA)</p>
                            <small class="text-warning">
                                <i class="fas fa-clock me-1"></i>{{ number_format($stats['pending_payments'], 0, ',', ' ') }} en attente
                            </small>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info bg-gradient rounded-circle p-3">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['this_month_revenue'], 0, ',', ' ') }}</h4>
                            <p class="text-muted mb-1">Ce Mois (FCFA)</p>
                            <small class="text-muted">
                                <i class="fas fa-calculator me-1"></i>Moy: {{ number_format($stats['avg_payment'] ?? 0, 0, ',', ' ') }}
                            </small>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="col-lg-8 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Tendances sur 12 Mois</h5></div><div class="card-body">
            <canvas id="trendsChart" width="400" height="200"></canvas>
        </div></div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statut des Pèlerins</h5></div><div class="card-body">
            <canvas id="pilgrimsStatusChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    {{-- Quick Reports Section --}}
    <div class="col-12 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Rapports Rapides</h5></div><div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-grid">
                        <a href="{{ route('reports.campaigns') }}" class="btn btn-outline-primary">
                            <i class="fas fa-flag me-2"></i>
                            Rapport Campagnes
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-grid">
                        <a href="{{ route('reports.payments') }}" class="btn btn-outline-success">
                            <i class="fas fa-credit-card me-2"></i>
                            Rapport Paiements
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-grid">
                        <a href="{{ route('reports.clients') }}" class="btn btn-outline-success">
                            <i class="fas fa-user-friends me-2"></i>
                            Rapport Clients
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-grid">
                        <a href="{{ route('reports.pilgrims') }}" class="btn btn-outline-warning">
                            <i class="fas fa-users me-2"></i>
                            Rapport Pèlerins
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-grid">
                        <a href="{{ route('reports.documents') }}" class="btn btn-outline-info">
                            <i class="fas fa-file-alt me-2"></i>
                            Rapport Documents
                        </a>
                    </div>
                </div>
            </div>
        </div></div>
    </div>

    {{-- Recent Activities --}}
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Paiements Récents</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Pèlerin</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                        <tr>
                            <td>
                                @if($payment->pilgrim->client)
                                    <strong>{{ $payment->pilgrim->client->full_name }}</strong>
                                    <br><small class="text-muted">{{ $payment->pilgrim->full_name }}</small>
                                @else
                                    {{ $payment->pilgrim->full_name }}
                                    <br><small class="text-warning">Client non associé</small>
                                @endif
                            </td>
                            <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun paiement récent</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Nouveaux Pèlerins</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Campagne</th>
                            <th>Date d'Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPilgrims as $pilgrim)
                        <tr>
                            <td>
                                @if($pilgrim->client)
                                    <strong>{{ $pilgrim->client->full_name }}</strong>
                                    <br><small class="text-muted">{{ $pilgrim->full_name }}</small>
                                @else
                                    {{ $pilgrim->full_name }}
                                    <br><small class="text-warning">Client non associé</small>
                                @endif
                            </td>
                            <td>{{ $pilgrim->campaign->name ?? 'N/A' }}</td>
                            <td>{{ $pilgrim->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucun nouveau pèlerin</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>

    {{-- Export Section --}}
    <div class="col-12">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Exports et Rapports Personnalisés</h5></div><div class="card-body">
            <form method="POST" action="{{ route('reports.export') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-3">
                        <label for="type" class="form-label">Type de Rapport</label>
                        <select class="form-select" name="type" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="campaigns">Campagnes</option>
                            <option value="payments">Paiements</option>
                            <option value="pilgrims">Pèlerins</option>
                            <option value="documents">Documents</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label for="format" class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="">-- Format --</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label for="from_date" class="form-label">Date Début</label>
                        <input type="date" class="form-control" name="from_date">
                    </div>

                    <div class="col-lg-2">
                        <label for="to_date" class="form-label">Date Fin</label>
                        <input type="date" class="form-control" name="to_date">
                    </div>

                    <div class="col-lg-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            Télécharger le Rapport
                        </button>
                    </div>
                </div>
            </form>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
const trendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyTrends['months']),
        datasets: [{
            label: 'Revenus (FCFA)',
            data: @json($monthlyTrends['revenues']),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            yAxisID: 'y'
        }, {
            label: 'Nombre de Pèlerins',
            data: @json($monthlyTrends['pilgrims']),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Revenus (FCFA)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Nombre de Pèlerins'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});

// Pilgrims Status Chart
const pilgrimsStatusCtx = document.getElementById('pilgrimsStatusChart').getContext('2d');
const pilgrimsStatusData = @json($stats['pilgrims_by_status']);
const pilgrimsStatusChart = new Chart(pilgrimsStatusCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(pilgrimsStatusData).map(status => status.charAt(0).toUpperCase() + status.slice(1)),
        datasets: [{
            data: Object.values(pilgrimsStatusData),
            backgroundColor: [
                '#007bff',
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