@extends('layouts.app')

@section('title', 'Rapport des Paiements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Paiements</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Rapport des Paiements</h1>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="payments">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="payments">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="col-12 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Filtres</h5></div><div class="card-body">
            <form method="GET" action="{{ route('reports.payments') }}">
                <div class="row">
                    <div class="col-lg-2">
                        <label for="from_date" class="form-label">Date Début</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-lg-2">
                        <label for="to_date" class="form-label">Date Fin</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-lg-2">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" name="status">
                            <option value="">Tous</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="payment_method" class="form-label">Mode de Paiement</label>
                        <select class="form-select" name="payment_method">
                            <option value="">Tous</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="check" {{ request('payment_method') === 'check' ? 'selected' : '' }}>Chèque</option>
                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                            <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Carte</option>
                        </select>
                    </div>
                    <div class="col-lg-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('reports.payments') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div></div>
    </div>

    {{-- Statistics --}}
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-success mb-1">{{ number_format($paymentStats['total_amount'], 0, ',', ' ') }}</h3>
                    <p class="text-muted mb-0">Total Encaissé (FCFA)</p>
                    <small class="text-muted">{{ $paymentStats['total_count'] }} paiements</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-warning mb-1">{{ number_format($paymentStats['pending_amount'], 0, ',', ' ') }}</h3>
                    <p class="text-muted mb-0">En Attente (FCFA)</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-danger mb-1">{{ number_format($paymentStats['cancelled_amount'], 0, ',', ' ') }}</h3>
                    <p class="text-muted mb-0">Annulé (FCFA)</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-info mb-1">{{ number_format($paymentStats['total_amount'] / max($paymentStats['total_count'], 1), 0, ',', ' ') }}</h3>
                    <p class="text-muted mb-0">Montant Moyen (FCFA)</p>
                </div></div>
            </div>
        </div>
    </div>

    {{-- Payment Methods Chart --}}
    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Répartition par Mode de Paiement</h5></div><div class="card-body">
            <canvas id="paymentMethodsChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques par Mode</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Mode</th>
                            <th>Nombre</th>
                            <th>Total (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentStats['by_method'] as $method)
                        <tr>
                            <td>
                                @switch($method->payment_method)
                                    @case('cash') Espèces @break
                                    @case('check') Chèque @break
                                    @case('bank_transfer') Virement @break
                                    @case('card') Carte @break
                                    @default {{ $method->payment_method }}
                                @endswitch
                            </td>
                            <td>{{ $method->count }}</td>
                            <td>{{ number_format($method->total, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>

    {{-- Payments Table --}}
    <div class="col-12">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Historique des Paiements</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Pèlerin</th>
                            <th>Campagne</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><strong>#{{ $payment->id }}</strong></td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</h6>
                                    @if($payment->pilgrim->email)
                                        <small class="text-muted">{{ $payment->pilgrim->email }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $payment->pilgrim->campaign->name ?? 'N/A' }}</td>
                            <td class="text-end">
                                <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>
                                @switch($payment->payment_method)
                                    @case('cash')
                                        <i class="fas fa-money-bill text-success me-1"></i>Espèces
                                        @break
                                    @case('check')
                                        <i class="fas fa-money-check text-info me-1"></i>Chèque
                                        @break
                                    @case('bank_transfer')
                                        <i class="fas fa-university text-primary me-1"></i>Virement
                                        @break
                                    @case('card')
                                        <i class="fas fa-credit-card text-warning me-1"></i>Carte
                                        @break
                                    @default
                                        {{ $payment->payment_method }}
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td>
                                <x-badge class="btn btn-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    @switch($payment->status)
                                        @case('completed') Terminé @break
                                        @case('cancelled') Annulé @break
                                        @default En attente
                                    @endswitch
                                </x-badge>
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-credit-card fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">Aucun paiement trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->withQueryString()->links() }}
                </div>
            @endif
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Payment Methods Chart
const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
const paymentMethodsData = @json($paymentStats['by_method']);

const methodLabels = paymentMethodsData.map(item => {
    switch(item.payment_method) {
        case 'cash': return 'Espèces';
        case 'check': return 'Chèque';
        case 'bank_transfer': return 'Virement';
        case 'card': return 'Carte';
        default: return item.payment_method;
    }
});

const methodAmounts = paymentMethodsData.map(item => item.total);

const paymentMethodsChart = new Chart(paymentMethodsCtx, {
    type: 'doughnut',
    data: {
        labels: methodLabels,
        datasets: [{
            data: methodAmounts,
            backgroundColor: [
                '#28a745',
                '#007bff',
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