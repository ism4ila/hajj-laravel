@extends('layouts.app')

@section('title', 'Checklist Documents - ' . $pilgrim->firstname . ' ' . $pilgrim->lastname)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.index') }}">Pèlerins</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.show', $pilgrim) }}">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('documents.index', $pilgrim) }}">Documents</a></li>
    <li class="breadcrumb-item active">Checklist</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="avatar bg-primary text-white rounded-circle me-3"
             style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
            {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
        </div>
        <div>
            <h1 class="h3 mb-0">Checklist Documents</h1>
            <p class="text-muted mb-0">
                {{ $pilgrim->firstname }} {{ $pilgrim->lastname }} - {{ $pilgrim->campaign?->name }}
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <x-button href="{{ route('documents.index', $pilgrim) }}" variant="outline-primary" icon="fas fa-edit">
            Modifier
        </x-button>
        <x-button href="{{ route('pilgrims.show', $pilgrim) }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<!-- Progress Summary -->
<div class="row mb-4">
    <div class="col-lg-4">
        <x-card class="text-center h-100 {{ $document && $document->documents_complete ? 'bg-success text-white' : 'bg-warning' }}">
            @php
                $totalDocuments = count(array_filter($documents, function($doc) { return $doc['required']; }));
                $completedDocuments = 0;
                if ($document) {
                    foreach ($documents as $key => $doc) {
                        if ($doc['required']) {
                            $fileField = $key . '_file';
                            if ($key === 'photo_file') {
                                if ($document->photo_file) $completedDocuments++;
                            } else {
                                if ($document->$fileField || $document->$key) $completedDocuments++;
                            }
                        }
                    }
                }
                $percentage = $totalDocuments > 0 ? ($completedDocuments / $totalDocuments) * 100 : 0;
            @endphp

            <div class="mb-3">
                <div class="progress-ring" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                    <svg width="100" height="100" style="transform: rotate(-90deg);">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="8"></circle>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="white" stroke-width="8"
                                stroke-dasharray="{{ 2 * 3.14159 * 40 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - $percentage / 100) }}"
                                style="transition: stroke-dashoffset 0.3s ease;"></circle>
                    </svg>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <div class="h4 mb-0">{{ number_format($percentage, 0) }}%</div>
                    </div>
                </div>
            </div>

            <h5>{{ $completedDocuments }}/{{ $totalDocuments }} Documents</h5>
            <p class="mb-0">
                @if($percentage >= 100)
                    Dossier complet
                @else
                    {{ $totalDocuments - $completedDocuments }} document(s) manquant(s)
                @endif
            </p>
        </x-card>
    </div>

    <div class="col-lg-8">
        <x-card title="📊 Récapitulatif" class="h-100">
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations Pèlerin</h6>
                    <ul class="list-unstyled">
                        <li><strong>Nom:</strong> {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</li>
                        <li><strong>Campagne:</strong> {{ $pilgrim->campaign?->name }}</li>
                        <li><strong>Type:</strong>
                            <x-badge variant="{{ $pilgrim->campaign?->type === 'hajj' ? 'primary' : 'info' }}">
                                {{ ucfirst($pilgrim->campaign?->type ?? 'N/A') }}
                            </x-badge>
                        </li>
                        @if($pilgrim->campaign)
                        <li><strong>Dates:</strong>
                            {{ \Carbon\Carbon::parse($pilgrim->campaign->departure_date)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($pilgrim->campaign->return_date)->format('d/m/Y') }}
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Statut Documents</h6>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>{{ $completedDocuments }}</strong> documents fournis
                        </li>
                        @if($totalDocuments - $completedDocuments > 0)
                        <li>
                            <i class="fas fa-times text-danger me-2"></i>
                            <strong>{{ $totalDocuments - $completedDocuments }}</strong> documents manquants
                        </li>
                        @endif
                        <li>
                            <i class="fas fa-calendar text-muted me-2"></i>
                            Dernière mise à jour: {{ $document ? $document->updated_at->format('d/m/Y à H:i') : 'Jamais' }}
                        </li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Documents Checklist -->
<x-card title="📋 Liste des Documents" class="mb-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="5%">Statut</th>
                    <th width="40%">Document</th>
                    <th width="30%">Description</th>
                    <th width="15%">Requis</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $key => $doc)
                    @php
                        $hasFile = false;
                        $hasData = false;
                        $fileName = '';

                        if ($document) {
                            if ($key === 'photo_file') {
                                $hasFile = !empty($document->photo_file);
                                $fileName = $hasFile ? basename($document->photo_file) : '';
                            } else {
                                $fileField = $key . '_file';
                                $hasFile = !empty($document->$fileField);
                                $hasData = !empty($document->$key);
                                $fileName = $hasFile ? basename($document->$fileField) : '';
                            }
                        }

                        $isComplete = ($key === 'photo_file') ? $hasFile : ($hasFile || $hasData);
                        $statusClass = $isComplete ? 'success' : ($doc['required'] ? 'danger' : 'secondary');
                        $statusIcon = $isComplete ? 'check-circle' : ($doc['required'] ? 'exclamation-circle' : 'circle');
                    @endphp

                    <tr class="{{ $isComplete ? 'table-success' : ($doc['required'] ? 'table-warning' : '') }}">
                        <td class="text-center">
                            <i class="fas fa-{{ $statusIcon }} text-{{ $statusClass }} fa-lg"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="{{ $doc['icon'] }} text-primary me-2"></i>
                                <div>
                                    <div class="fw-semibold">{{ $doc['name'] }}</div>
                                    @if($hasFile)
                                        <small class="text-muted">
                                            <i class="fas fa-file me-1"></i>{{ $fileName }}
                                        </small>
                                    @endif
                                    @if($hasData && $key !== 'photo_file')
                                        <small class="text-muted d-block">
                                            <i class="fas fa-info-circle me-1"></i>{{ $document->$key }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $doc['description'] }}</small>
                        </td>
                        <td>
                            @if($doc['required'])
                                <x-badge variant="danger">Requis</x-badge>
                            @else
                                <x-badge variant="secondary">Optionnel</x-badge>
                            @endif
                        </td>
                        <td>
                            @if($hasFile)
                                <div class="btn-group" role="group">
                                    <a href="{{ route('documents.download', [$pilgrim, str_replace('_file', '', $key)]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    <form method="POST"
                                          action="{{ route('documents.delete-file', [$pilgrim, str_replace('_file', '', $key)]) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Supprimer ce fichier ?')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    
                                </div>
                            @else
                                <small class="text-muted">Aucun fichier</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>

<!-- Action Buttons -->
<div class="row">
    <div class="col-md-8">
        @if(!$document || !$document->documents_complete)
        <x-card title="⚠️ Actions Requises" class="border-warning">
            <ul class="list-unstyled mb-3">
                @if(!$document || count($document->missing_documents) > 0)
                    @foreach(($document ? $document->missing_documents : []) as $missing)
                        <li><i class="fas fa-arrow-right text-warning me-2"></i>Fournir: {{ $missing }}</li>
                    @endforeach
                @endif
            </ul>

            
            <x-button href="{{ route('documents.index', $pilgrim) }}" variant="warning">
                <i class="fas fa-plus me-1"></i>Ajouter les Documents Manquants
            </x-button>
            
        </x-card>
        @endif
    </div>

    <div class="col-md-4">
        <x-card title="🎯 Actions Rapides">
            <div class="d-grid gap-2">
                
                <x-button href="{{ route('documents.index', $pilgrim) }}" variant="primary">
                    <i class="fas fa-edit me-1"></i>Modifier les Documents
                </x-button>
                

                <x-button href="#" variant="outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Imprimer la Checklist
                </x-button>

                @if($document && $document->documents_complete)
                <x-button href="#" variant="outline-success">
                    <i class="fas fa-certificate me-1"></i>Générer Attestation
                </x-button>
                @endif
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .d-flex, .breadcrumb, .card-header .d-flex {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    body {
        font-size: 12px;
    }

    .table {
        font-size: 11px;
    }
}

.progress-ring circle {
    transition: stroke-dashoffset 0.5s ease-in-out;
}

.table-success {
    background-color: rgba(25, 135, 84, 0.1);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
</style>
@endpush