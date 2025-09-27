@extends('layouts.app')

@section('title', 'Rapport des Campagnes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Campagnes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Rapport des Campagnes</h1>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="campaigns">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="campaigns">
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
                    <h3 class="text-primary mb-1">{{ $campaigns->count() }}</h3>
                    <p class="text-muted mb-0">Total Campagnes</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-success mb-1">{{ $campaigns->where('status', 'active')->count() }}</h3>
                    <p class="text-muted mb-0">Campagnes Actives</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-info mb-1">{{ $campaigns->sum('pilgrims_count') }}</h3>
                    <p class="text-muted mb-0">Total Pèlerins</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-warning mb-1">{{ number_format($campaigns->sum('total_revenue'), 0, ',', ' ') }}</h3>
                    <p class="text-muted mb-0">Revenus Totaux (FCFA)</p>
                </div></div>
            </div>
        </div>
    </div>

    {{-- Campaigns Table --}}
    <div class="col-12">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Détails des Campagnes</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Campagne</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Prix (FCFA)</th>
                            <th>Dates</th>
                            <th>Pèlerins</th>
                            <th>Revenus (FCFA)</th>
                            <th>Payé (FCFA)</th>
                            <th>Restant (FCFA)</th>
                            <th>Completion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $campaign->name }}</h6>
                                        <small class="text-muted">ID: {{ $campaign->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-badge class="btn btn-{{ $campaign->type === 'hajj' ? 'primary' : 'info' }}">
                                    {{ ucfirst($campaign->type) }}
                                </x-badge>
                            </td>
                            <td>
                                <x-badge class="btn btn-{{ $campaign->status === 'active' ? 'success' : ($campaign->status === 'completed' ? 'primary' : 'secondary') }}">
                                    {{ ucfirst($campaign->status) }}
                                </x-badge>
                            </td>
                            <td>
                                <div>
                                    <div><strong>Classique:</strong> {{ number_format($campaign->price_classic, 0, ',', ' ') }}</div>
                                    <div><strong>VIP:</strong> {{ number_format($campaign->price_vip, 0, ',', ' ') }}</div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div><small><strong>Départ:</strong> {{ \Carbon\Carbon::parse($campaign->departure_date)->format('d/m/Y') }}</small></div>
                                    <div><small><strong>Retour:</strong> {{ \Carbon\Carbon::parse($campaign->return_date)->format('d/m/Y') }}</small></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <h5 class="mb-0">{{ $campaign->pilgrims_count }}</h5>
                                <small class="text-muted">pèlerins</small>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($campaign->total_revenue, 0, ',', ' ') }}</strong>
                            </td>
                            <td class="text-end text-success">
                                <strong>{{ number_format($campaign->total_paid, 0, ',', ' ') }}</strong>
                            </td>
                            <td class="text-end text-danger">
                                <strong>{{ number_format($campaign->total_remaining, 0, ',', ' ') }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar
                                                {{ $campaign->completion_rate >= 100 ? 'bg-success' :
                                                   ($campaign->completion_rate >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                 style="width: {{ min($campaign->completion_rate, 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ number_format($campaign->completion_rate, 1) }}%</small>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-flag fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">Aucune campagne trouvée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Additional charts can be added here if needed
</script>
@endpush