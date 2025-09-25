@extends('layouts.app')

@section('title', 'Gestion des Pèlerins')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pèlerins</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Pèlerins</h1>
        <p class="text-muted mb-0">Gérez les inscriptions et informations des pèlerins</p>
    </div>
    @can('manage-pilgrims')
    <div>
        <div class="btn-group me-2">
            <x-button href="{{ route('pilgrims.exportExcel') }}" variant="outline-success" icon="fas fa-file-excel">
                Exporter
            </x-button>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-1"></i>Importer
            </button>
        </div>
        <x-button href="{{ route('pilgrims.create') }}" variant="primary" icon="fas fa-user-plus">
            Nouveau Pèlerin
        </x-button>
    </div>
    @endcan
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-primary text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $pilgrims->total() }}</div>
                    <div class="text-white-75">Total Pèlerins</div>
                </div>
                <i class="fas fa-users fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-success text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">
                        {{ $pilgrims->where('status', 'active')->count() + $pilgrims->where('status', 'completed')->count() }}
                    </div>
                    <div class="text-white-75">Confirmés</div>
                </div>
                <i class="fas fa-check-circle fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-warning text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $pilgrims->where('status', 'pending')->count() }}</div>
                    <div class="text-white-75">En Attente</div>
                </div>
                <i class="fas fa-clock fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-info text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">
                        {{ $pilgrims->sum(function($p) { return $p->remaining_amount > 0 ? 1 : 0; }) }}
                    </div>
                    <div class="text-white-75">Paiements Incomplets</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
</div>

<!-- Filters -->
<x-card class="mb-4">
    <form method="GET" action="{{ route('pilgrims.index') }}" class="row g-3">
        <div class="col-md-3">
            <x-form.input
                name="search"
                placeholder="Rechercher par nom, email..."
                :value="request('search')"
                prepend="search"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="campaign"
                :options="['' => 'Toutes les campagnes'] + $campaigns->pluck('name', 'id')->toArray()"
                :value="request('campaign')"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="status"
                :options="[
                    '' => 'Tous les statuts',
                    'pending' => 'En attente',
                    'active' => 'Actif',
                    'completed' => 'Terminé',
                    'cancelled' => 'Annulé'
                ]"
                :value="request('status')"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="gender"
                :options="[
                    '' => 'Tous les genres',
                    'male' => 'Homme',
                    'female' => 'Femme'
                ]"
                :value="request('gender')"
            />
        </div>
        <div class="col-md-3">
            <div class="d-grid gap-2 d-md-flex">
                <x-button type="submit" variant="outline-primary" class="flex-fill">
                    Filtrer
                </x-button>
                @if(request()->hasAny(['search', 'campaign', 'status', 'gender']))
                    <a href="{{ route('pilgrims.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-card>

<!-- Pilgrims List -->
@if($pilgrims->count() > 0)
    <x-card>
        <x-table.table
            :headers="[
                ['label' => 'Pèlerin', 'width' => '25%'],
                ['label' => 'Campagne', 'width' => '20%'],
                ['label' => 'Contact', 'width' => '20%'],
                ['label' => 'Paiements', 'width' => '15%'],
                ['label' => 'Statut', 'width' => '10%'],
                ['label' => 'Actions', 'width' => '10%']
            ]"
            responsive>
            @foreach($pilgrims as $pilgrim)
                <tr>
                    <!-- Pilgrim Info -->
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary text-white rounded-circle me-3"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</div>
                                <small class="text-muted">
                                    {{ $pilgrim->gender === 'male' ? '♂' : '♀' }}
                                    {{ \Carbon\Carbon::parse($pilgrim->date_of_birth)->age }} ans
                                </small>
                            </div>
                        </div>
                    </td>

                    <!-- Campaign -->
                    <td>
                        <div class="fw-semibold">{{ $pilgrim->campaign->name ?? 'N/A' }}</div>
                        <small class="text-muted">
                            <x-badge variant="{{ $pilgrim->campaign?->type === 'hajj' ? 'primary' : 'info' }}" pill>
                                {{ ucfirst($pilgrim->campaign?->type ?? 'N/A') }}
                            </x-badge>
                        </small>
                    </td>

                    <!-- Contact -->
                    <td>
                        @if($pilgrim->email)
                            <div><i class="fas fa-envelope text-muted me-1"></i>{{ $pilgrim->email }}</div>
                        @endif
                        @if($pilgrim->phone)
                            <div><i class="fas fa-phone text-muted me-1"></i>{{ $pilgrim->phone }}</div>
                        @endif
                    </td>

                    <!-- Payments -->
                    <td>
                        <div class="small">
                            <div>Total: <strong>{{ number_format($pilgrim->total_amount, 0, ',', ' ') }} DH</strong></div>
                            <div class="text-success">Payé: {{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} DH</div>
                            @if($pilgrim->remaining_amount > 0)
                                <div class="text-danger">Reste: {{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</div>
                            @endif
                        </div>
                        @php
                            $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                            $progressClass = $percentage >= 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <div class="progress mt-1" style="height: 4px;">
                            <div class="progress-bar {{ $progressClass }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </td>

                    <!-- Status -->
                    <td>
                        <x-badge variant="{{
                            $pilgrim->status === 'active' ? 'success' :
                            ($pilgrim->status === 'completed' ? 'primary' :
                            ($pilgrim->status === 'cancelled' ? 'danger' : 'warning'))
                        }}">
                            {{ ucfirst($pilgrim->status) }}
                        </x-badge>
                    </td>

                    <!-- Actions -->
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('pilgrims.show', $pilgrim) }}">
                                        <i class="fas fa-eye me-2"></i>Voir détails
                                    </a>
                                </li>
                                @can('manage-pilgrims')
                                <li>
                                    <a class="dropdown-item" href="{{ route('pilgrims.edit', $pilgrim) }}">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('pilgrims.destroy', $pilgrim) }}"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pèlerin ?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $pilgrims->links() }}
        </div>
    </x-card>
@else
    <!-- Empty State -->
    <x-card class="text-center py-5">
        <i class="fas fa-users text-muted fa-3x mb-3"></i>
        <h4>Aucun pèlerin trouvé</h4>
        <p class="text-muted mb-4">
            @if(request()->hasAny(['search', 'campaign', 'status', 'gender']))
                Aucun pèlerin ne correspond à vos critères de recherche.
                <br>
                <a href="{{ route('pilgrims.index') }}" class="btn btn-link">Voir tous les pèlerins</a>
            @else
                Commencez par inscrire votre premier pèlerin.
            @endif
        </p>
        @can('manage-pilgrims')
        @if(!request()->hasAny(['search', 'campaign', 'status', 'gender']))
            <x-button href="{{ route('pilgrims.create') }}" variant="primary" icon="fas fa-user-plus">
                Inscrire un pèlerin
            </x-button>
        @endif
        @endcan
    </x-card>
@endif

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('pilgrims.importExcel') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Importer des Pèlerins</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Fichier Excel</label>
                        <input type="file" class="form-control" id="import_file" name="import_file"
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Formats acceptés: .xlsx, .xls, .csv</div>
                    </div>
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Assurez-vous que votre fichier contient les colonnes : prénom, nom, genre, date de naissance, etc.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Importer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection