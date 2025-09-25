@extends('layouts.app')

@section('title', 'Nouveau Paiement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Paiements</a></li>
    <li class="breadcrumb-item active">Nouveau Paiement</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Enregistrer un Nouveau Paiement</h1>
        <p class="text-muted mb-0">Saisissez les détails du paiement</p>
    </div>
    <x-button href="{{ route('payments.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
        Retour
    </x-button>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('payments.store') }}" id="payment-form">
            @csrf

            <!-- Pilgrim Selection -->
            <x-card title="👤 Sélection du Pèlerin" class="mb-4">
                <x-form.select
                    name="pilgrim_id"
                    label="Pèlerin"
                    :options="['' => '-- Sélectionner un pèlerin --'] + $pilgrims->mapWithKeys(function($pilgrim) {
                        return [$pilgrim->id => $pilgrim->firstname . ' ' . $pilgrim->lastname . ' - ' . number_format($pilgrim->remaining_amount, 0, ',', ' ') . ' DH restants'];
                    })->toArray()"
                    :value="$pilgrim?->id ?? old('pilgrim_id')"
                    required
                    help="Seuls les pèlerins avec des paiements restants sont listés"
                />

                @if($pilgrim)
                    <div class="alert alert-info mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</strong><br>
                                <small class="text-muted">{{ $pilgrim->campaign?->name }}</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="mb-1">
                                    <span class="text-muted">Total:</span>
                                    <strong>{{ number_format($pilgrim->total_amount, 0, ',', ' ') }} DH</strong>
                                </div>
                                <div class="mb-1">
                                    <span class="text-success">Payé:</span>
                                    <strong>{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} DH</strong>
                                </div>
                                <div>
                                    <span class="text-danger">Restant:</span>
                                    <strong>{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="pilgrim-info" class="mt-3 d-none">
                    <!-- Dynamic pilgrim info will be loaded here -->
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
                            :value="old('amount')"
                            required
                            append="DH"
                            step="0.01"
                            min="0.01"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="payment_date"
                            type="date"
                            label="Date du paiement"
                            :value="old('payment_date', today()->format('Y-m-d'))"
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
                                '' => '-- Sélectionner --',
                                'cash' => 'Espèces',
                                'check' => 'Chèque',
                                'bank_transfer' => 'Virement bancaire',
                                'card' => 'Carte bancaire'
                            ]"
                            :value="old('payment_method')"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="reference"
                            label="Référence"
                            placeholder="N° chèque, référence virement..."
                            :value="old('reference')"
                            help="Optionnel - Numéro de chèque, référence de virement, etc."
                        />
                    </div>
                </div>

                <x-form.textarea
                    name="notes"
                    label="Notes"
                    placeholder="Notes complémentaires..."
                    :value="old('notes')"
                    rows="3"
                    help="Informations complémentaires sur ce paiement"
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
                    :value="old('status', 'completed')"
                    required
                    help="Sélectionnez 'Terminé' pour les paiements confirmés"
                />

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Important :</strong> Les montants du pèlerin ne seront mis à jour que si le statut est "Terminé".
                </div>
            </x-card>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <x-button type="submit" variant="primary" icon="fas fa-save">
                    Enregistrer le Paiement
                </x-button>
                <x-button href="{{ route('payments.index') }}" variant="outline-secondary">
                    Annuler
                </x-button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <!-- Help Card -->
        <x-card title="💡 Conseils" class="mb-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <strong>Montant :</strong> Vérifiez que le montant ne dépasse pas le solde restant
                </li>
                <li class="mb-2">
                    <strong>Date :</strong> Utilisez la date réelle du paiement
                </li>
                <li class="mb-2">
                    <strong>Référence :</strong> Notez le numéro de chèque ou de virement
                </li>
                <li class="mb-2">
                    <strong>Statut :</strong> "Terminé" pour confirmer, "En attente" pour vérification
                </li>
                <li>
                    <strong>Notes :</strong> Ajoutez des informations utiles pour le suivi
                </li>
            </ul>
        </x-card>

        <!-- Payment Methods Info -->
        <x-card title="🔍 Modes de Paiement" class="mb-4">
            <div class="small">
                <div class="mb-2">
                    <i class="fas fa-money-bill text-success me-2"></i>
                    <strong>Espèces :</strong> Paiement immédiat
                </div>
                <div class="mb-2">
                    <i class="fas fa-money-check text-info me-2"></i>
                    <strong>Chèque :</strong> Indiquez le numéro
                </div>
                <div class="mb-2">
                    <i class="fas fa-university text-primary me-2"></i>
                    <strong>Virement :</strong> Référence bancaire
                </div>
                <div>
                    <i class="fas fa-credit-card text-warning me-2"></i>
                    <strong>Carte :</strong> Terminal de paiement
                </div>
            </div>
        </x-card>

        <!-- Quick Stats -->
        <div id="quick-stats" class="d-none">
            <x-card title="📈 Résumé" class="mb-4">
                <div id="stats-content">
                    <!-- Dynamic stats will be loaded here -->
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pilgrimSelect = document.querySelector('[name="pilgrim_id"]');
    const amountInput = document.querySelector('[name="amount"]');
    const pilgrimInfo = document.getElementById('pilgrim-info');
    const quickStats = document.getElementById('quick-stats');
    const statsContent = document.getElementById('stats-content');

    // Pilgrim data (passed from controller)
    const pilgrimsData = @json($pilgrims->keyBy('id'));

    function updatePilgrimInfo() {
        const selectedId = pilgrimSelect.value;

        if (selectedId && pilgrimsData[selectedId]) {
            const pilgrim = pilgrimsData[selectedId];

            // Show pilgrim info
            let html = `
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>${pilgrim.firstname} ${pilgrim.lastname}</strong><br>
                            <small class="text-muted">${pilgrim.campaign ? pilgrim.campaign.name : 'Aucune campagne'}</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="mb-1">
                                <span class="text-muted">Total:</span>
                                <strong>${parseFloat(pilgrim.total_amount).toLocaleString('fr-FR')} DH</strong>
                            </div>
                            <div class="mb-1">
                                <span class="text-success">Payé:</span>
                                <strong>${parseFloat(pilgrim.paid_amount).toLocaleString('fr-FR')} DH</strong>
                            </div>
                            <div>
                                <span class="text-danger">Restant:</span>
                                <strong>${parseFloat(pilgrim.remaining_amount).toLocaleString('fr-FR')} DH</strong>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            pilgrimInfo.innerHTML = html;
            pilgrimInfo.classList.remove('d-none');

            // Set max amount
            amountInput.max = pilgrim.remaining_amount;
            amountInput.placeholder = `Max: ${parseFloat(pilgrim.remaining_amount).toLocaleString('fr-FR')} DH`;

            // Show quick stats
            const percentage = pilgrim.total_amount > 0 ? (pilgrim.paid_amount / pilgrim.total_amount) * 100 : 0;
            const progressClass = percentage >= 100 ? 'bg-success' : (percentage >= 50 ? 'bg-warning' : 'bg-danger');

            statsContent.innerHTML = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progression du paiement</small>
                        <small>${percentage.toFixed(1)}%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar ${progressClass}" style="width: ${percentage}%"></div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="h5 mb-0">${parseFloat(pilgrim.remaining_amount).toLocaleString('fr-FR')} DH</div>
                    <small class="text-muted">Montant restant</small>
                </div>
            `;
            quickStats.classList.remove('d-none');

        } else {
            pilgrimInfo.classList.add('d-none');
            quickStats.classList.add('d-none');
            amountInput.max = '';
            amountInput.placeholder = '0';
        }
    }

    // Validate amount
    function validateAmount() {
        const selectedId = pilgrimSelect.value;
        const amount = parseFloat(amountInput.value);

        if (selectedId && pilgrimsData[selectedId] && amount > 0) {
            const pilgrim = pilgrimsData[selectedId];

            if (amount > pilgrim.remaining_amount) {
                amountInput.setCustomValidity(`Le montant ne peut pas dépasser ${parseFloat(pilgrim.remaining_amount).toLocaleString('fr-FR')} DH`);
            } else {
                amountInput.setCustomValidity('');
            }
        } else {
            amountInput.setCustomValidity('');
        }
    }

    // Event listeners
    pilgrimSelect.addEventListener('change', updatePilgrimInfo);
    amountInput.addEventListener('input', validateAmount);

    // Initial load
    updatePilgrimInfo();
});
</script>
@endpush