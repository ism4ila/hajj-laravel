@extends('layouts.app')

@section('title', 'Détails du Paiement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Paiements</a></li>
    <li class="breadcrumb-item active">Paiement #{{ $payment->id }}</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Paiement #{{ $payment->id }}</h1>
        <p class="text-muted mb-0">
            {{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}
            •
            <x-badge variant="{{
                $payment->status === 'completed' ? 'success' :
                ($payment->status === 'cancelled' ? 'danger' : 'warning')
            }}">
                {{ ucfirst($payment->status) }}
            </x-badge>
        </p>
    </div>
    <div class="d-flex gap-2">
        
        <x-button href="{{ route('payments.edit', $payment) }}" variant="outline-primary" icon="fas fa-edit">
            Modifier
        </x-button>
        
        
        <!-- Dropdown pour choisir le template de reçu -->
        <div class="btn-group">
            <x-button href="{{ route('payments.receipt', $payment) }}" variant="success" icon="fas fa-receipt">
                Télécharger Reçu
            </x-button>
            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('payments.receipt', ['payment' => $payment, 'template' => 'compact']) }}">
                    <i class="fas fa-file-pdf me-2 text-danger"></i>Compact (1 page)
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('payments.receipt', ['payment' => $payment, 'template' => 'premium']) }}">
                    <i class="fas fa-star me-2 text-warning"></i>Premium
                </a></li>
                <li><a class="dropdown-item" href="{{ route('payments.receipt', ['payment' => $payment, 'template' => 'receipt']) }}">
                    <i class="fas fa-file-alt me-2 text-info"></i>Standard
                </a></li>
            </ul>
        </div>
        
        <x-button href="{{ route('payments.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Payment Information -->
        <x-card title="💰 Informations du Paiement" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Montant</small>
                        <div class="h4 text-success mb-0">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Date du paiement</small>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Mode de paiement</small>
                        <div class="d-flex align-items-center">
                            @switch($payment->payment_method)
                                @case('cash')
                                    <i class="fas fa-money-bill text-success me-2"></i>Espèces
                                    @break
                                @case('check')
                                    <i class="fas fa-money-check text-info me-2"></i>Chèque
                                    @break
                                @case('bank_transfer')
                                    <i class="fas fa-university text-primary me-2"></i>Virement bancaire
                                    @break
                                @case('card')
                                    <i class="fas fa-credit-card text-warning me-2"></i>Carte bancaire
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Statut</small>
                        <div>
                            <x-badge variant="{{
                                $payment->status === 'completed' ? 'success' :
                                ($payment->status === 'cancelled' ? 'danger' : 'warning')
                            }}" class="px-3 py-2">
                                @switch($payment->status)
                                    @case('completed')
                                        <i class="fas fa-check me-1"></i>Paiement terminé
                                        @break
                                    @case('cancelled')
                                        <i class="fas fa-times me-1"></i>Paiement annulé
                                        @break
                                    @default
                                        <i class="fas fa-clock me-1"></i>En attente
                                @endswitch
                            </x-badge>
                        </div>
                    </div>
                </div>
            </div>

            @if($payment->reference)
            <div class="mb-3">
                <small class="text-muted">Référence</small>
                <div class="fw-semibold">{{ $payment->reference }}</div>
            </div>
            @endif

            @if($payment->notes)
            <div class="mb-3">
                <small class="text-muted">Notes</small>
                <div class="border rounded p-3 bg-light">{{ $payment->notes }}</div>
            </div>
            @endif

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Enregistré le</small>
                        <div>{{ $payment->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @if($payment->updated_at != $payment->created_at)
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Dernière modification</small>
                        <div>{{ $payment->updated_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Pilgrim Information -->
        <x-card title="👤 Informations du Pèlerin" class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar bg-primary text-white rounded-circle me-3"
                     style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    {{ substr($payment->pilgrim->firstname, 0, 1) }}{{ substr($payment->pilgrim->lastname, 0, 1) }}
                </div>
                <div>
                    <h5 class="mb-1">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</h5>
                    <p class="text-muted mb-0">{{ $payment->pilgrim->campaign?->name }}</p>
                </div>
            </div>

            <div class="row">
                @if($payment->pilgrim->email)
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <div>
                            <i class="fas fa-envelope text-muted me-1"></i>
                            <a href="mailto:{{ $payment->pilgrim->email }}">{{ $payment->pilgrim->email }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($payment->pilgrim->phone)
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Téléphone</small>
                        <div>
                            <i class="fas fa-phone text-muted me-1"></i>
                            <a href="tel:{{ $payment->pilgrim->phone }}">{{ $payment->pilgrim->phone }}</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="text-center mt-3">
                <x-button href="{{ route('pilgrims.show', $payment->pilgrim) }}" variant="outline-primary" size="sm">
                    Voir le dossier complet
                </x-button>
            </div>
        </x-card>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Financial Summary -->
        <x-card title="💰 Résumé Financier" class="mb-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span>Montant total du voyage:</span>
                    <strong>{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between text-success">
                    <span>Total payé:</span>
                    <strong>{{ number_format($payment->pilgrim->paid_amount, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between {{ $payment->pilgrim->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                    <span>Montant restant:</span>
                    <strong>{{ number_format($payment->pilgrim->remaining_amount, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>

            @php
                $percentage = $payment->pilgrim->total_amount > 0 ? ($payment->pilgrim->paid_amount / $payment->pilgrim->total_amount) * 100 : 0;
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

            <hr>

            <div class="alert {{ $payment->status === 'completed' ? 'alert-success' : ($payment->status === 'cancelled' ? 'alert-danger' : 'alert-warning') }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Ce paiement:</span>
                    <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>
        </x-card>

        <!-- Campaign Information -->
        @if($payment->pilgrim->campaign)
        <x-card title="🏕️ Campagne" class="mb-4">
            <div class="mb-3">
                <div class="fw-semibold">{{ $payment->pilgrim->campaign->name }}</div>
                <x-badge variant="{{ $payment->pilgrim->campaign->type === 'hajj' ? 'primary' : 'info' }}" class="mt-1">
                    {{ ucfirst($payment->pilgrim->campaign->type) }}
                </x-badge>
            </div>

            <hr>

            <div class="row text-center">
                <div class="col-6">
                    <div class="text-muted small">Départ</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->departure_date)->format('d/m/Y') }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Retour</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($payment->pilgrim->campaign->return_date)->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="mt-3 text-center">
                <x-button href="{{ route('campaigns.show', $payment->pilgrim->campaign) }}" variant="outline-primary" size="sm">
                    Voir la campagne
                </x-button>
            </div>
        </x-card>
        @endif

        <!-- Quick Actions -->
        <x-card title="⚡ Actions Rapides" class="mb-4">
            <div class="d-grid gap-2">
                
                <x-button href="{{ route('payments.edit', $payment) }}" variant="outline-primary" size="sm">
                    <i class="fas fa-edit me-1"></i>Modifier ce paiement
                </x-button>

                @if($payment->pilgrim->remaining_amount > 0)
                <x-button href="{{ route('payments.create', ['pilgrim' => $payment->pilgrim->id]) }}" variant="outline-success" size="sm">
                    <i class="fas fa-plus me-1"></i>Nouveau paiement
                </x-button>
                @endif
                

                
                <x-button href="{{ route('payments.receipt', $payment) }}" variant="outline-info" size="sm">
                    <i class="fas fa-receipt me-1"></i>Générer reçu
                </x-button>
                

                <x-button href="#" variant="outline-secondary" size="sm" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Imprimer
                </x-button>

                
                <hr>

                <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action est irréversible.')"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i>Supprimer ce paiement
                    </button>
                </form>
                
            </div>
        </x-card>

        <!-- Payment History -->
        <x-card title="📋 Historique" class="mb-4">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}"></div>
                    <div class="timeline-content">
                        <div class="fw-semibold">Paiement {{ $payment->status === 'completed' ? 'confirmé' : ($payment->status === 'cancelled' ? 'annulé' : 'en attente') }}</div>
                        <small class="text-muted">{{ $payment->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>

                @if($payment->updated_at != $payment->created_at)
                <div class="timeline-item">
                    <div class="timeline-marker bg-info"></div>
                    <div class="timeline-content">
                        <div class="fw-semibold">Paiement modifié</div>
                        <small class="text-muted">{{ $payment->updated_at->format('d/m/Y à H:i') }}</small>
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

@media print {
    .btn, .d-flex, .breadcrumb {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    body {
        font-size: 12px;
    }
}
</style>
@endpush