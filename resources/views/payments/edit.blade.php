@extends('layouts.app')

@section('title', 'Modifier le Paiement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Paiements</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payments.show', $payment) }}">Paiement #{{ $payment->id }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Modifier le Paiement #{{ $payment->id }}</h1>
        <p class="text-muted mb-0">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</p>
    </div>
    <div class="d-flex gap-2">
        <x-button href="{{ route('payments.show', $payment) }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('payments.update', $payment) }}" id="payment-form">
            @csrf
            @method('PUT')

            <!-- Pilgrim Selection -->
            <x-card title="👤 Pèlerin" class="mb-4">
                <x-form.select
                    name="pilgrim_id"
                    label="Pèlerin"
                    :options="$pilgrims->mapWithKeys(function($pilgrim) use ($payment) {
                        $remaining = $pilgrim->id == $payment->pilgrim_id
                            ? $pilgrim->remaining_amount + $payment->amount
                            : $pilgrim->remaining_amount;
                        return [$pilgrim->id => $pilgrim->firstname . ' ' . $pilgrim->lastname . ' - ' . number_format($remaining, 0, ',', ' ') . ' FCFA disponibles'];
                    })->toArray()"
                    :value="old('pilgrim_id', $payment->pilgrim_id)"
                    required
                />

                <div class="alert alert-info mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</strong><br>
                            <small class="text-muted">{{ $payment->pilgrim->campaign?->name }}</small>
                        </div>
                        <div class="col-md-6 text-end">
                            @php
                                $adjustedRemaining = $payment->pilgrim->remaining_amount + ($payment->status === 'completed' ? $payment->amount : 0);
                            @endphp
                            <div class="mb-1">
                                <span class="text-muted">Total:</span>
                                <strong>{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }} FCFA</strong>
                            </div>
                            <div class="mb-1">
                                <span class="text-success">Payé:</span>
                                <strong>{{ number_format($payment->pilgrim->paid_amount - ($payment->status === 'completed' ? $payment->amount : 0), 0, ',', ' ') }} FCFA</strong>
                            </div>
                            <div>
                                <span class="text-danger">Disponible:</span>
                                <strong>{{ number_format($adjustedRemaining, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Payment Details -->
            <x-card title="💰 Détails du Paiement" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="amount"
                            type="number"
                            label="Montant"
                            placeholder="0"
                            :value="old('amount', $payment->amount)"
                            required
                            append="FCFA"
                            step="0.01"
                            min="0.01"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="payment_date"
                            type="date"
                            label="Date du paiement"
                            :value="old('payment_date', $payment->payment_date)"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form.select
                            name="payment_method"
                            label="Mode de paiement"
                            :options="[
                                'cash' => 'Espèces',
                                'check' => 'Chèque',
                                'bank_transfer' => 'Virement bancaire',
                                'card' => 'Carte bancaire'
                            ]"
                            :value="old('payment_method', $payment->payment_method)"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="reference"
                            label="Référence"
                            placeholder="N° chèque, référence virement..."
                            :value="old('reference', $payment->reference)"
                        />
                    </div>
                </div>

                <x-form.textarea
                    name="notes"
                    label="Notes"
                    placeholder="Notes complémentaires..."
                    :value="old('notes', $payment->notes)"
                    rows="3"
                />
            </x-card>

            <!-- Payment Status -->
            <x-card title="📊 Statut du Paiement" class="mb-4">
                <x-form.select
                    name="status"
                    label="Statut"
                    :options="[
                        'completed' => 'Terminé (confirmé)',
                        'pending' => 'En attente (à confirmer)',
                        'cancelled' => 'Annulé'
                    ]"
                    :value="old('status', $payment->status)"
                    required
                />

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Attention :</strong> Modifier le statut affectera les montants du pèlerin.
                    <ul class="mt-2 mb-0">
                        <li>"Terminé" : Le montant est déduit du solde restant</li>
                        <li>"En attente" / "Annulé" : Le montant n'affecte pas le solde</li>
                    </ul>
                </div>
            </x-card>

            <!-- Change Summary -->
            <x-card title="📝 Résumé des Modifications" class="mb-4 border-warning">
                <div class="alert alert-light">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Valeurs actuelles :</h6>
                            <ul class="list-unstyled">
                                <li><strong>Montant :</strong> {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</li>
                                <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</li>
                                <li><strong>Mode :</strong>
                                    @switch($payment->payment_method)
                                        @case('cash') Espèces @break
                                        @case('check') Chèque @break
                                        @case('bank_transfer') Virement @break
                                        @case('card') Carte @break
                                    @endswitch
                                </li>
                                <li><strong>Statut :</strong>
                                    <x-badge variant="{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($payment->status) }}
                                    </x-badge>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Impact sur le pèlerin :</h6>
                            <p class="text-muted mb-0">
                                Les modifications seront automatiquement répercutées sur le solde du pèlerin selon le nouveau statut sélectionné.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <x-button type="submit" variant="primary" icon="fas fa-save">
                    Sauvegarder les Modifications
                </x-button>
                <x-button href="{{ route('payments.show', $payment) }}" variant="outline-secondary">
                    Annuler
                </x-button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <!-- Current Status -->
        <x-card title="📊 Statut Actuel" class="mb-4">
            <div class="text-center mb-3">
                <x-badge variant="{{
                    $payment->status === 'completed' ? 'success' :
                    ($payment->status === 'cancelled' ? 'danger' : 'warning')
                }}" class="px-3 py-2">
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
            </div>

            <div class="text-center">
                <div class="h4 mb-0 text-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                </div>
                <small class="text-muted">Montant actuel</small>
            </div>

            <hr>

            <div class="small">
                <div class="mb-2">
                    <strong>Créé :</strong> {{ $payment->created_at->format('d/m/Y à H:i') }}
                </div>
                @if($payment->updated_at != $payment->created_at)
                <div class="mb-2">
                    <strong>Modifié :</strong> {{ $payment->updated_at->format('d/m/Y à H:i') }}
                </div>
                @endif
            </div>
        </x-card>

        <!-- Pilgrim Summary -->
        <x-card title="👤 Résumé Pèlerin" class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar bg-primary text-white rounded-circle me-3"
                     style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    {{ substr($payment->pilgrim->firstname, 0, 1) }}{{ substr($payment->pilgrim->lastname, 0, 1) }}
                </div>
                <div>
                    <div class="fw-semibold">{{ $payment->pilgrim->firstname }} {{ $payment->pilgrim->lastname }}</div>
                    <small class="text-muted">{{ $payment->pilgrim->campaign?->name }}</small>
                </div>
            </div>

            @php
                $adjustedPaid = $payment->pilgrim->paid_amount - ($payment->status === 'completed' ? $payment->amount : 0);
                $adjustedRemaining = $payment->pilgrim->remaining_amount + ($payment->status === 'completed' ? $payment->amount : 0);
                $percentage = $payment->pilgrim->total_amount > 0 ? ($adjustedPaid / $payment->pilgrim->total_amount) * 100 : 0;
            @endphp

            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span>Total:</span>
                    <strong>{{ number_format($payment->pilgrim->total_amount, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between text-success">
                    <span>Payé (hors ce paiement):</span>
                    <strong>{{ number_format($adjustedPaid, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between text-danger">
                    <span>Disponible:</span>
                    <strong>{{ number_format($adjustedRemaining, 0, ',', ' ') }} FCFA</strong>
                </div>
            </div>

            <div class="progress mb-2">
                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="text-center">
                <small class="text-muted">{{ number_format($percentage, 1) }}% payé (sans ce paiement)</small>
            </div>
        </x-card>

        <!-- Warning Card -->
        <x-card title="⚠️ Important" class="border-warning mb-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    La modification du montant ou du statut affectera automatiquement le solde du pèlerin
                </li>
                <li class="mb-2">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Un paiement "Terminé" est déduit du montant restant à payer
                </li>
                <li>
                    <i class="fas fa-shield-alt text-success me-2"></i>
                    Toutes les modifications sont tracées dans l'historique
                </li>
            </ul>
        </x-card>

        <!-- Danger Zone -->
        <x-card title="🗑️ Zone Dangereuse" class="border-danger mb-4">
            <p class="text-muted small mb-3">
                Supprimer ce paiement est irréversible et restituera le montant au solde du pèlerin.
            </p>

            <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action est irréversible et le montant sera restitué au solde du pèlerin.')"
                  class="d-grid">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash me-1"></i>Supprimer le paiement
                </button>
            </form>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pilgrimSelect = document.querySelector('[name="pilgrim_id"]');
    const amountInput = document.querySelector('[name="amount"]');

    // Pilgrim data
    const pilgrimsData = @json($pilgrims->keyBy('id'));
    const currentPayment = @json($payment);

    function validateAmount() {
        const selectedId = pilgrimSelect.value;
        const amount = parseFloat(amountInput.value);

        if (selectedId && pilgrimsData[selectedId] && amount > 0) {
            const pilgrim = pilgrimsData[selectedId];

            // Calculate available amount considering current payment
            let availableAmount;
            if (selectedId == currentPayment.pilgrim_id) {
                availableAmount = pilgrim.remaining_amount + (currentPayment.status === 'completed' ? currentPayment.amount : 0);
            } else {
                availableAmount = pilgrim.remaining_amount;
            }

            if (amount > availableAmount) {
                amountInput.setCustomValidity(`Le montant ne peut pas dépasser ${availableAmount.toLocaleString('fr-FR')} FCFA`);
            } else {
                amountInput.setCustomValidity('');
            }
        } else {
            amountInput.setCustomValidity('');
        }
    }

    // Event listeners
    pilgrimSelect.addEventListener('change', validateAmount);
    amountInput.addEventListener('input', validateAmount);

    // Initial validation
    validateAmount();
});
</script>
@endpush