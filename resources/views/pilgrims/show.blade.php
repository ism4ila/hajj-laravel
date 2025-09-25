@extends('layouts.app')

@section('title', 'Détails du Pèlerin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.index') }}">Pèlerins</a></li>
    <li class="breadcrumb-item active">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="avatar bg-primary text-white rounded-circle me-3"
             style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
        </div>
        <div>
            <h1 class="h3 mb-0">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</h1>
            <p class="text-muted mb-0">
                {{ $pilgrim->gender === 'male' ? '♂' : '♀' }}
                {{ \Carbon\Carbon::parse($pilgrim->date_of_birth)->age }} ans
                •
                <x-badge variant="{{
                    $pilgrim->status === 'active' ? 'success' :
                    ($pilgrim->status === 'completed' ? 'primary' :
                    ($pilgrim->status === 'cancelled' ? 'danger' : 'warning'))
                }}">
                    {{ ucfirst($pilgrim->status) }}
                </x-badge>
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        @can('manage-pilgrims')
        <x-button href="{{ route('pilgrims.edit', $pilgrim) }}" variant="outline-primary" icon="fas fa-edit">
            Modifier
        </x-button>
        @endcan
        <x-button href="{{ route('pilgrims.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <x-card title="👤 Informations Personnelles" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Prénom</small>
                        <div class="fw-semibold">{{ $pilgrim->firstname }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Nom</small>
                        <div class="fw-semibold">{{ $pilgrim->lastname }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Genre</small>
                        <div>
                            <i class="fas fa-{{ $pilgrim->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                            {{ $pilgrim->gender === 'male' ? 'Homme' : 'Femme' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Date de naissance</small>
                        <div>{{ \Carbon\Carbon::parse($pilgrim->date_of_birth)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($pilgrim->date_of_birth)->age }} ans)</div>
                    </div>
                </div>
            </div>

            @if($pilgrim->address)
            <div class="mb-3">
                <small class="text-muted">Adresse</small>
                <div>{{ $pilgrim->address }}</div>
            </div>
            @endif
        </x-card>

        <!-- Contact Information -->
        <x-card title="📞 Coordonnées" class="mb-4">
            <div class="row">
                @if($pilgrim->email)
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <div>
                            <i class="fas fa-envelope text-muted me-1"></i>
                            <a href="mailto:{{ $pilgrim->email }}">{{ $pilgrim->email }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($pilgrim->phone)
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Téléphone</small>
                        <div>
                            <i class="fas fa-phone text-muted me-1"></i>
                            <a href="tel:{{ $pilgrim->phone }}">{{ $pilgrim->phone }}</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Emergency Contact -->
        <x-card title="🚨 Contact d'Urgence" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Nom</small>
                        <div class="fw-semibold">{{ $pilgrim->emergency_contact }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Téléphone</small>
                        <div>
                            <i class="fas fa-phone text-muted me-1"></i>
                            <a href="tel:{{ $pilgrim->emergency_phone }}">{{ $pilgrim->emergency_phone }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Payment History -->
        <x-card title="💰 Historique des Paiements" class="mb-4">
            @if($payments->count() > 0)
                <x-table.table
                    :headers="[
                        ['label' => 'Date', 'width' => '20%'],
                        ['label' => 'Montant', 'width' => '20%'],
                        ['label' => 'Mode', 'width' => '20%'],
                        ['label' => 'Statut', 'width' => '20%'],
                        ['label' => 'Actions', 'width' => '20%']
                    ]"
                    responsive>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} DH</strong></td>
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
                                @endswitch
                            </td>
                            <td>
                                <x-badge variant="{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    @switch($payment->status)
                                        @case('completed')
                                            <i class="fas fa-check me-1"></i>Terminé
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times me-1"></i>Annulé
                                            @break
                                        @default
                                            <i class="fas fa-clock me-1"></i>En attente
                                    @endswitch
                                </x-badge>
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-table.table>

                <div class="mt-3 text-center">
                    <a href="{{ route('payments.index', ['pilgrim' => $pilgrim->id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-1"></i>Voir tous les paiements
                    </a>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-credit-card fa-2x mb-3"></i>
                    <p>Aucun paiement enregistré</p>
                    @can('manage-payments')
                    <x-button href="{{ route('payments.create', ['pilgrim' => $pilgrim->id]) }}" variant="outline-primary" size="sm">
                        Ajouter un paiement
                    </x-button>
                    @endcan
                </div>
            @endif
        </x-card>

        <!-- Documents -->
        <x-card title="📄 Documents" class="mb-4">
            @if($documents->count() > 0)
                <div class="row">
                    @foreach($documents as $document)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt text-primary fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $document->name }}</div>
                                        <small class="text-muted">{{ $document->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Télécharger</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-folder-open fa-2x mb-3"></i>
                    <p>Aucun document uploadé</p>
                    @can('manage-documents')
                    <x-button href="{{ route('documents.index', $pilgrim) }}" variant="outline-primary" size="sm">
                        Gérer les documents
                    </x-button>
                    @endcan
                </div>
            @endif
        </x-card>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Campaign Information -->
        <x-card title="🏕️ Campagne & Catégorie" class="mb-4">
            @if($pilgrim->campaign)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">{{ $pilgrim->campaign->name }}</div>
                            <div class="d-flex gap-2 mt-2">
                                <x-badge variant="{{ $pilgrim->campaign->type === 'hajj' ? 'primary' : 'info' }}">
                                    {{ ucfirst($pilgrim->campaign->type) }}
                                </x-badge>
                                <x-badge variant="{{ $pilgrim->category === 'vip' ? 'warning' : 'success' }}">
                                    {{ $pilgrim->category === 'vip' ? '🥇 VIP' : '🥉 Classique' }}
                                </x-badge>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="text-center">
                        <div class="h4 mb-1 {{ $pilgrim->category === 'vip' ? 'text-warning' : 'text-success' }}">
                            {{ number_format($pilgrim->total_amount, 0, ',', ' ') }} DH
                        </div>
                        <small class="text-muted">Tarif {{ $pilgrim->category === 'vip' ? 'VIP' : 'Classique' }}</small>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-muted small">Départ</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($pilgrim->campaign->departure_date)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Retour</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="row text-center mt-2">
                    <div class="col-12">
                        <div class="text-muted small">Années</div>
                        <div class="fw-semibold">{{ $pilgrim->campaign->year_hijri }}H / {{ $pilgrim->campaign->year_gregorian }}G</div>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <x-button href="{{ route('campaigns.show', $pilgrim->campaign) }}" variant="outline-primary" size="sm">
                        Voir la campagne
                    </x-button>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fas fa-exclamation-triangle"></i>
                    Aucune campagne assignée
                </div>
            @endif
        </x-card>

        <!-- Payment Summary -->
        <x-card title="💰 Résumé Financier" class="mb-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span>Montant total:</span>
                    <strong>{{ number_format($pilgrim->total_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between text-success">
                    <span>Montant payé:</span>
                    <strong>{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between {{ $pilgrim->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                    <span>Montant restant:</span>
                    <strong>{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>

            @php
                $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                $progressClass = $percentage >= 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
            @endphp

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Progression du paiement</small>
                    <small>{{ number_format($percentage, 1) }}%</small>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            @can('manage-payments')
            @if($pilgrim->remaining_amount > 0)
            <x-button href="{{ route('payments.create', ['pilgrim' => $pilgrim->id]) }}" variant="primary" size="sm" class="w-100">
                <i class="fas fa-plus me-1"></i>Ajouter un paiement
            </x-button>
            @endif
            @endcan
        </x-card>

        <!-- Quick Actions -->
        <x-card title="⚡ Actions Rapides" class="mb-4">
            <div class="d-grid gap-2">
                @can('manage-pilgrims')
                <x-button href="{{ route('pilgrims.edit', $pilgrim) }}" variant="outline-primary" size="sm">
                    <i class="fas fa-edit me-1"></i>Modifier les informations
                </x-button>
                @endcan

                @can('manage-payments')
                <x-button href="{{ route('payments.index', ['pilgrim' => $pilgrim->id]) }}" variant="outline-success" size="sm">
                    <i class="fas fa-credit-card me-1"></i>Gérer les paiements
                </x-button>
                @endcan

                @can('manage-documents')
                <x-button href="{{ route('documents.index', $pilgrim) }}" variant="outline-info" size="sm">
                    <i class="fas fa-folder me-1"></i>Gérer les documents
                </x-button>
                @endcan

                <hr>

                <x-button href="#" variant="outline-secondary" size="sm">
                    <i class="fas fa-print me-1"></i>Imprimer le dossier
                </x-button>

                <x-button href="#" variant="outline-secondary" size="sm">
                    <i class="fas fa-download me-1"></i>Exporter en PDF
                </x-button>

                @can('manage-pilgrims')
                <hr>

                <form method="POST" action="{{ route('pilgrims.destroy', $pilgrim) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pèlerin ? Cette action est irréversible.')"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i>Supprimer le pèlerin
                    </button>
                </form>
                @endcan
            </div>
        </x-card>

        <!-- Recent Activity -->
        <x-card title="📋 Activité Récente" class="mb-4">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <div class="fw-semibold">Inscription créée</div>
                        <small class="text-muted">{{ $pilgrim->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>

                @if($pilgrim->updated_at != $pilgrim->created_at)
                <div class="timeline-item">
                    <div class="timeline-marker bg-warning"></div>
                    <div class="timeline-content">
                        <div class="fw-semibold">Informations mises à jour</div>
                        <small class="text-muted">{{ $pilgrim->updated_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>
                @endif
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -8px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 6px;
    border-left: 3px solid #dee2e6;
}
</style>
@endpush