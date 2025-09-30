@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('breadcrumb')
    <li class="breadcrumb-item active">Paiements</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Paiements</h1>
        <p class="text-muted mb-0">Enregistrez et suivez tous les paiements des pèlerins</p>
    </div>
    
    <div>
        <x-button href="{{ route('payments.create') }}" variant="primary" icon="fas fa-plus">
            Nouveau Paiement
        </x-button>
    </div>
    
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-success text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($stats['total_payments'], 0, ',', ' ') }} FCFA</div>
                    <div class="text-white-75">Total Encaissé</div>
                </div>
                <i class="fas fa-money-bill-wave fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-warning text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ number_format($stats['pending_payments'], 0, ',', ' ') }} FCFA</div>
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
                    <div class="h4 mb-0">{{ number_format($stats['today_payments'], 0, ',', ' ') }} FCFA</div>
                    <div class="text-white-75">Aujourd'hui</div>
                </div>
                <i class="fas fa-calendar-day fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <x-card class="bg-primary text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="h4 mb-0">{{ $stats['count_payments'] }}</div>
                    <div class="text-white-75">Transactions</div>
                </div>
                <i class="fas fa-receipt fa-2x text-white-25"></i>
            </div>
        </x-card>
    </div>
</div>

<!-- Filters -->
<x-card class="mb-4">
    <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
        <div class="col-md-2">
            <x-form.input
                name="search"
                placeholder="Rechercher..."
                :value="request('search')"
                prepend="search"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="client"
                :options="['' => 'Tous les clients'] + $clients->mapWithKeys(function($c) { return [$c->id => $c->full_name]; })->toArray()"
                :value="request('client')"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="pilgrim"
                :options="['' => 'Tous les pèlerins'] + $pilgrims->pluck('full_name', 'id')->toArray()"
                :value="request('pilgrim')"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="status"
                :options="[
                    '' => 'Tous les statuts',
                    'pending' => 'En attente',
                    'completed' => 'Terminé',
                    'cancelled' => 'Annulé'
                ]"
                :value="request('status')"
            />
        </div>
        <div class="col-md-2">
            <x-form.select
                name="method"
                :options="[
                    '' => 'Tous les modes',
                    'cash' => 'Espèces',
                    'check' => 'Chèque',
                    'bank_transfer' => 'Virement',
                    'card' => 'Carte'
                ]"
                :value="request('method')"
            />
        </div>
        <div class="col-md-3">
            <div class="d-grid gap-2 d-md-flex">
                <x-button type="submit" variant="outline-primary" class="flex-fill">
                    Filtrer
                </x-button>
                @if(request()->hasAny(['search', 'pilgrim', 'status', 'method']))
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-card>

<!-- Payments List -->
@if($payments->count() > 0)
    <x-card>
        <x-table.table
            id="paymentsTable"
            :headers="[
                ['label' => 'Client / Pèlerin', 'width' => '25%'],
                ['label' => 'Montant', 'width' => '15%'],
                ['label' => 'Mode', 'width' => '15%'],
                ['label' => 'Date', 'width' => '15%'],
                ['label' => 'Statut', 'width' => '10%'],
                ['label' => 'Actions', 'width' => '20%']
            ]"
            responsive>
            @foreach($payments as $payment)
                <tr>
                    <!-- Client / Pilgrim -->
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary text-white rounded-circle me-3"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                @if($payment->pilgrim->client)
                                    {{ substr($payment->pilgrim->client->firstname, 0, 1) }}{{ substr($payment->pilgrim->client->lastname, 0, 1) }}
                                @else
                                    {{ substr($payment->pilgrim->firstname, 0, 1) }}{{ substr($payment->pilgrim->lastname, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                @if($payment->pilgrim->client)
                                    <div class="fw-semibold">
                                        <i class="fas fa-user text-muted me-1"></i>{{ $payment->pilgrim->client->full_name }}
                                    </div>
                                    <small class="text-muted">Pèlerin: {{ $payment->pilgrim->full_name }}</small>
                                @else
                                    <div class="fw-semibold">{{ $payment->pilgrim->full_name }}</div>
                                    <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Client non associé</small>
                                @endif
                                <br><small class="text-muted">{{ $payment->pilgrim->campaign?->name }}</small>
                            </div>
                        </div>
                    </td>

                    <!-- Amount -->
                    <td>
                        <div class="fw-semibold">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                        @if($payment->reference)
                            <small class="text-muted">Réf: {{ $payment->reference }}</small>
                        @endif
                    </td>

                    <!-- Payment Method -->
                    <td>
                        <div class="d-flex align-items-center">
                            @switch($payment->payment_method)
                                @case('cash')
                                    <i class="fas fa-money-bill text-success me-2"></i>Espèces
                                    @break
                                @case('check')
                                    <i class="fas fa-money-check text-info me-2"></i>Chèque
                                    @break
                                @case('bank_transfer')
                                    <i class="fas fa-university text-primary me-2"></i>Virement
                                    @break
                                @case('card')
                                    <i class="fas fa-credit-card text-warning me-2"></i>Carte
                                    @break
                            @endswitch
                        </div>
                    </td>

                    <!-- Date -->
                    <td>
                        <div>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                    </td>

                    <!-- Status -->
                    <td>
                        <x-badge variant="{{
                            $payment->status === 'completed' ? 'success' :
                            ($payment->status === 'cancelled' ? 'danger' : 'warning')
                        }}">
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

                    <!-- Actions -->
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('payments.show', $payment) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('payments.edit', $payment) }}"
                               class="btn btn-sm btn-outline-warning"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            
                            <div class="btn-group" role="group">
                                <a href="{{ route('payments.receipt', ['payment' => $payment, 'template' => 'compact']) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Télécharger Reçu">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Templates</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('payments.receipt', ['payment' => $payment, 'template' => 'compact']) }}">
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>Compact
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
                            
                            
                            <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $payments->links() }}
        </div>
    </x-card>
@else
    <!-- Empty State -->
    <x-card class="text-center py-5">
        <i class="fas fa-money-bill-wave text-muted fa-3x mb-3"></i>
        <h4>Aucun paiement trouvé</h4>
        <p class="text-muted mb-4">
            @if(request()->hasAny(['search', 'pilgrim', 'status', 'method']))
                Aucun paiement ne correspond à vos critères de recherche.
                <br>
                <a href="{{ route('payments.index') }}" class="btn btn-link">Voir tous les paiements</a>
            @else
                Commencez par enregistrer votre premier paiement.
            @endif
        </p>
        
        @if(!request()->hasAny(['search', 'pilgrim', 'status', 'method']))
            <x-button href="{{ route('payments.create') }}" variant="primary" icon="fas fa-plus">
                Enregistrer un paiement
            </x-button>
        @endif
        
    </x-card>
@endif
@endsection