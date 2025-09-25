@extends('layouts.app')

@section('title', 'Modifier le Pèlerin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.index') }}">Pèlerins</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.show', $pilgrim) }}">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
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
            <h1 class="h3 mb-0">Modifier {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</h1>
            <p class="text-muted mb-0">Mettez à jour les informations du pèlerin</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <x-button href="{{ route('pilgrims.show', $pilgrim) }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('pilgrims.update', $pilgrim) }}">
            @csrf
            @method('PUT')

            <!-- Campaign Selection -->
            <x-card title="🏕️ Campagne" class="mb-4">
                <x-form.select
                    name="campaign_id"
                    label="Campagne"
                    :options="['' => '-- Sélectionner une campagne --'] + $campaigns->pluck('name', 'id')->toArray()"
                    :value="old('campaign_id', $pilgrim->campaign_id)"
                    required
                    help="Choisissez la campagne pour ce pèlerin"
                />

                @if($pilgrim->campaign)
                    <div class="alert alert-info mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $pilgrim->campaign->name }}</strong>
                                <x-badge variant="{{ $pilgrim->campaign->type === 'hajj' ? 'primary' : 'info' }}" class="ms-2">
                                    {{ ucfirst($pilgrim->campaign->type) }}
                                </x-badge>
                            </div>
                            <div class="text-end">
                                <div class="h6 mb-0">{{ number_format($pilgrim->campaign->price, 0, ',', ' ') }} DH</div>
                                <small class="text-muted">Prix campagne</small>
                            </div>
                        </div>
                    </div>
                @endif
            </x-card>

            <!-- Personal Information -->
            <x-card title="👤 Informations Personnelles" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="firstname"
                            label="Prénom"
                            placeholder="Prénom du pèlerin"
                            :value="old('firstname', $pilgrim->firstname)"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="lastname"
                            label="Nom"
                            placeholder="Nom de famille"
                            :value="old('lastname', $pilgrim->lastname)"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form.select
                            name="gender"
                            label="Genre"
                            :options="[
                                '' => '-- Sélectionner --',
                                'male' => 'Homme',
                                'female' => 'Femme'
                            ]"
                            :value="old('gender', $pilgrim->gender)"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="date_of_birth"
                            type="date"
                            label="Date de naissance"
                            :value="old('date_of_birth', $pilgrim->date_of_birth)"
                            required
                        />
                    </div>
                </div>
            </x-card>

            <!-- Contact Information -->
            <x-card title="📞 Coordonnées" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="email"
                            type="email"
                            label="Email"
                            placeholder="email@exemple.com"
                            :value="old('email', $pilgrim->email)"
                            prepend="envelope"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="phone"
                            label="Téléphone"
                            placeholder="+212 6XX XX XX XX"
                            :value="old('phone', $pilgrim->phone)"
                            prepend="phone"
                        />
                    </div>
                </div>

                <x-form.textarea
                    name="address"
                    label="Adresse"
                    placeholder="Adresse complète..."
                    :value="old('address', $pilgrim->address)"
                    rows="3"
                />
            </x-card>

            <!-- Emergency Contact -->
            <x-card title="🚨 Contact d'Urgence" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="emergency_contact"
                            label="Nom du contact"
                            placeholder="Nom complet"
                            :value="old('emergency_contact', $pilgrim->emergency_contact)"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="emergency_phone"
                            label="Téléphone d'urgence"
                            placeholder="+212 6XX XX XX XX"
                            :value="old('emergency_phone', $pilgrim->emergency_phone)"
                            required
                            prepend="phone"
                        />
                    </div>
                </div>
            </x-card>

            <!-- Payment Information -->
            <x-card title="💰 Informations de Paiement" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="total_amount"
                            type="number"
                            label="Montant total"
                            placeholder="50000"
                            :value="old('total_amount', $pilgrim->total_amount)"
                            required
                            append="DH"
                            step="0.01"
                            min="0"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="paid_amount"
                            type="number"
                            label="Montant déjà payé"
                            placeholder="0"
                            :value="old('paid_amount', $pilgrim->paid_amount)"
                            append="DH"
                            step="0.01"
                            min="0"
                        />
                    </div>
                </div>

                <div id="remaining-amount-display" class="alert alert-info mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Montant restant à payer:</span>
                        <strong id="remaining-amount">{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</strong>
                    </div>
                </div>
            </x-card>

            <!-- Status -->
            <x-card title="📊 Statut" class="mb-4">
                <x-form.select
                    name="status"
                    label="Statut du pèlerin"
                    :options="[
                        'pending' => 'En attente',
                        'active' => 'Actif',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé'
                    ]"
                    :value="old('status', $pilgrim->status)"
                    required
                    help="Mettez à jour le statut selon l'avancement du dossier"
                />

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Créé le</div>
                        <div>{{ $pilgrim->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    @if($pilgrim->updated_at != $pilgrim->created_at)
                    <div class="col-md-4">
                        <div class="text-muted small">Dernière modification</div>
                        <div>{{ $pilgrim->updated_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <x-button type="submit" variant="primary" icon="fas fa-save">
                    Sauvegarder les Modifications
                </x-button>
                <x-button href="{{ route('pilgrims.show', $pilgrim) }}" variant="outline-secondary">
                    Annuler
                </x-button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <!-- Current Status -->
        <x-card title="📈 Statut Actuel" class="mb-4">
            <div class="text-center mb-3">
                <x-badge variant="{{
                    $pilgrim->status === 'active' ? 'success' :
                    ($pilgrim->status === 'completed' ? 'primary' :
                    ($pilgrim->status === 'cancelled' ? 'danger' : 'warning'))
                }}" class="px-3 py-2">
                    {{ ucfirst($pilgrim->status) }}
                </x-badge>
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

            <hr>

            <div class="row text-center">
                <div class="col-4">
                    <div class="text-muted small">Âge</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($pilgrim->date_of_birth)->age }} ans</div>
                </div>
                <div class="col-4">
                    <div class="text-muted small">Genre</div>
                    <div class="fw-semibold">{{ $pilgrim->gender === 'male' ? 'H' : 'F' }}</div>
                </div>
                <div class="col-4">
                    <div class="text-muted small">Campagne</div>
                    <div class="fw-semibold">{{ $pilgrim->campaign?->type === 'hajj' ? 'Hajj' : 'Omra' }}</div>
                </div>
            </div>
        </x-card>

        <!-- Payment Summary -->
        <x-card title="💰 Résumé Financier" class="mb-4">
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span>Total:</span>
                    <strong>{{ number_format($pilgrim->total_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between text-success">
                    <span>Payé:</span>
                    <strong>{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between {{ $pilgrim->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                    <span>Restant:</span>
                    <strong>{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</strong>
                </div>
            </div>
        </x-card>

        <!-- Help Card -->
        <x-card title="💡 Conseils" class="mb-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2">
                    <strong>Statut :</strong>
                    <ul class="mt-1">
                        <li><span class="badge bg-warning">Pending</span> : En cours de traitement</li>
                        <li><span class="badge bg-success">Active</span> : Confirmé et actif</li>
                        <li><span class="badge bg-primary">Completed</span> : Pèlerinage terminé</li>
                        <li><span class="badge bg-danger">Cancelled</span> : Annulé</li>
                    </ul>
                </li>
                <li class="mb-2">
                    <strong>Paiements :</strong> Les montants sont automatiquement calculés
                </li>
                <li>
                    <strong>Données :</strong> Vérifiez l'exactitude avant de sauvegarder
                </li>
            </ul>
        </x-card>

        <!-- Danger Zone -->
        <x-card title="⚠️ Zone Dangereuse" class="border-danger mb-4">
            <p class="text-muted small mb-3">
                Les actions suivantes sont irréversibles. Procédez avec prudence.
            </p>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i>Supprimer le pèlerin
                </button>
            </div>
        </x-card>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la Suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le pèlerin <strong>{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</strong> ?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cette action est <strong>irréversible</strong>. Toutes les données associées seront perdues définitivement.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('pilgrims.destroy', $pilgrim) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Supprimer Définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalAmountInput = document.querySelector('[name="total_amount"]');
    const paidAmountInput = document.querySelector('[name="paid_amount"]');
    const remainingAmount = document.getElementById('remaining-amount');
    const remainingDisplay = document.getElementById('remaining-amount-display');

    function updateRemainingAmount() {
        const total = parseFloat(totalAmountInput.value) || 0;
        const paid = parseFloat(paidAmountInput.value) || 0;
        const remaining = total - paid;

        remainingAmount.textContent = remaining.toLocaleString('fr-FR') + ' DH';

        // Update alert class based on remaining amount
        remainingDisplay.className = 'alert mt-3 ' + (remaining <= 0 ? 'alert-success' : remaining <= total * 0.5 ? 'alert-warning' : 'alert-info');
    }

    // Event listeners
    totalAmountInput.addEventListener('input', updateRemainingAmount);
    paidAmountInput.addEventListener('input', updateRemainingAmount);

    // Initial update
    updateRemainingAmount();
});
</script>
@endpush