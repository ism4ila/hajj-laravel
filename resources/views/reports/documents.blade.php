@extends('layouts.app')

@section('title', 'Rapport des Documents')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Documents</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Rapport des Documents</h1>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="documents">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="documents">
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
                    <h3 class="text-primary mb-1">{{ $documentStats['total_pilgrims'] }}</h3>
                    <p class="text-muted mb-0">Total Pèlerins</p>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-success mb-1">{{ $documentStats['documents_complete'] }}</h3>
                    <p class="text-muted mb-0">Dossiers Complets</p>
                    <small class="text-muted">{{ number_format(($documentStats['documents_complete'] / max($documentStats['total_pilgrims'], 1)) * 100, 1) }}%</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-warning mb-1">{{ $documentStats['documents_incomplete'] }}</h3>
                    <p class="text-muted mb-0">Dossiers Incomplets</p>
                    <small class="text-muted">{{ number_format(($documentStats['documents_incomplete'] / max($documentStats['total_pilgrims'], 1)) * 100, 1) }}%</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card class="h-100"><div class="card-body">
                    <h3 class="text-info mb-1">{{ number_format(($documentStats['documents_complete'] / max($documentStats['total_pilgrims'], 1)) * 100, 1) }}%</h3>
                    <p class="text-muted mb-0">Taux de Complétude</p>
                </div></div>
            </div>
        </div>
    </div>

    {{-- Document Completion Chart --}}
    <div class="col-lg-6 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>État Global des Dossiers</h5></div><div class="card-body">
            <canvas id="completionChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card class="h-100"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Complétude par Type de Document</h5></div><div class="card-body">
            <canvas id="documentTypesChart" width="300" height="200"></canvas>
        </div></div>
    </div>

    {{-- Document Types Statistics --}}
    <div class="col-12 mb-4">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées par Type de Document</h5></div><div class="card-body">
            <div class="row">
                @php
                    $documentTypeLabels = [
                        'passport' => ['label' => 'Passeport', 'icon' => 'fas fa-passport', 'color' => 'primary'],
                        'visa' => ['label' => 'Visa', 'icon' => 'fas fa-stamp', 'color' => 'success'],
                        'vaccination' => ['label' => 'Vaccination', 'icon' => 'fas fa-syringe', 'color' => 'warning'],
                        'photo' => ['label' => 'Photo d\'Identité', 'icon' => 'fas fa-camera', 'color' => 'info'],
                        'medical' => ['label' => 'Certificat Médical', 'icon' => 'fas fa-heartbeat', 'color' => 'danger']
                    ];
                @endphp

                @foreach($documentStats['by_document_type'] as $type => $stats)
                    @php $typeInfo = $documentTypeLabels[$type] ?? ['label' => ucfirst($type), 'icon' => 'fas fa-file', 'color' => 'secondary']; @endphp
                    <div class="col-lg-2-4 col-md-4 col-sm-6 mb-3">
                        <div class="card class="h-100"><div class="card-body">
                            <div class="mb-3">
                                <div class="bg-{{ $typeInfo['color'] }} bg-gradient rounded-circle p-3 d-inline-flex">
                                    <i class="{{ $typeInfo['icon'] }} text-white fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">{{ $typeInfo['label'] }}</h5>

                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-success mb-1">{{ $stats['complete'] }}</h4>
                                    <small class="text-muted">Complets</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-danger mb-1">{{ $stats['incomplete'] }}</h4>
                                    <small class="text-muted">Manquants</small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $typeInfo['color'] }}"
                                         style="width: {{ ($stats['complete'] / max(($stats['complete'] + $stats['incomplete']), 1)) * 100 }}%">
                                    </div>
                                </div>
                                <small class="text-muted mt-1">
                                    {{ number_format(($stats['complete'] / max(($stats['complete'] + $stats['incomplete']), 1)) * 100, 1) }}% complet
                                </small>
                            </div>
                        </div></div>
                    </div>
                @endforeach
            </div>
        </div></div>
    </div>

    {{-- Detailed Document Status Table --}}
    <div class="col-12">
        <div class="card"><div class="card-header bg-primary text-white"><h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>État Détaillé des Documents par Pèlerin</h5></div><div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="documentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Pèlerin</th>
                            <th>Campagne</th>
                            <th>Passeport</th>
                            <th>Visa</th>
                            <th>Vaccination</th>
                            <th>Photo</th>
                            <th>Médical</th>
                            <th>Statut Global</th>
                            <th>Dernière MAJ</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pilgrims as $pilgrim)
                            @php
                                $documents = json_decode($pilgrim->documents ?? '{}', true);
                                $documentTypes = ['passport', 'visa', 'vaccination', 'photo', 'medical'];
                                $allComplete = true;
                                foreach ($documentTypes as $type) {
                                    if (!isset($documents[$type]) || empty($documents[$type])) {
                                        $allComplete = false;
                                        break;
                                    }
                                }
                            @endphp
                        <tr class="{{ !$allComplete ? 'table-warning' : '' }}">
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</h6>
                                    <small class="text-muted">ID: {{ $pilgrim->id }}</small>
                                </div>
                            </td>
                            <td>{{ $pilgrim->campaign->name ?? 'N/A' }}</td>

                            @foreach($documentTypes as $type)
                                <td class="text-center">
                                    @if(isset($documents[$type]) && !empty($documents[$type]))
                                        <i class="fas fa-check-circle text-success" title="Document disponible"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger" title="Document manquant"></i>
                                    @endif
                                </td>
                            @endforeach

                            <td>
                                @if($allComplete)
                                    <x-badge class="btn btn-success">Complet</x-badge>
                                @else
                                    <x-badge class="btn btn-warning">Incomplet</x-badge>
                                @endif
                            </td>
                            <td>{{ $pilgrim->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('documents.index', $pilgrim) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-folder-open me-1"></i>
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Légende :</strong>
                    <i class="fas fa-check-circle text-success mx-2"></i> Document disponible
                    <i class="fas fa-times-circle text-danger mx-2"></i> Document manquant
                    <span class="mx-2">Les lignes surlignées en jaune indiquent des dossiers incomplets.</span>
                </div>
            </div>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Completion Overview Chart
const completionCtx = document.getElementById('completionChart').getContext('2d');
const completionChart = new Chart(completionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Dossiers Complets', 'Dossiers Incomplets'],
        datasets: [{
            data: [{{ $documentStats['documents_complete'] }}, {{ $documentStats['documents_incomplete'] }}],
            backgroundColor: [
                '#28a745',
                '#ffc107'
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

// Document Types Chart
const documentTypesCtx = document.getElementById('documentTypesChart').getContext('2d');
const documentTypesData = @json($documentStats['by_document_type']);

const typeLabels = Object.keys(documentTypesData).map(type => {
    switch(type) {
        case 'passport': return 'Passeport';
        case 'visa': return 'Visa';
        case 'vaccination': return 'Vaccination';
        case 'photo': return 'Photo';
        case 'medical': return 'Médical';
        default: return type;
    }
});

const completeData = Object.values(documentTypesData).map(stats => stats.complete);
const incompleteData = Object.values(documentTypesData).map(stats => stats.incomplete);

const documentTypesChart = new Chart(documentTypesCtx, {
    type: 'bar',
    data: {
        labels: typeLabels,
        datasets: [{
            label: 'Complets',
            data: completeData,
            backgroundColor: '#28a745'
        }, {
            label: 'Manquants',
            data: incompleteData,
            backgroundColor: '#dc3545'
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
                position: 'bottom'
            }
        }
    }
});

// Add DataTable functionality for better table management
document.addEventListener('DOMContentLoaded', function() {
    // Simple filtering functionality
    const filterButtons = document.querySelectorAll('.btn-filter');
    const tableRows = document.querySelectorAll('#documentsTable tbody tr');

    // You can add more advanced filtering here if needed
});
</script>

<style>
.col-lg-2-4 {
    flex: 0 0 auto;
    width: 20%;
}

@media (max-width: 991.98px) {
    .col-lg-2-4 {
        width: 33.333333%;
    }
}

@media (max-width: 575.98px) {
    .col-lg-2-4 {
        width: 50%;
    }
}
</style>
@endpush