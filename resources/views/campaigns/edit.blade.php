@extends('layouts.app')

@section('title', 'Modifier Campagne')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('campaigns.show', $campaign) }}">{{ $campaign->name }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Modifier la Campagne</h1>
        <p class="text-muted mb-0">{{ $campaign->name }}</p>
    </div>
    <div>
        <x-button href="{{ route('campaigns.show', $campaign) }}" variant="outline-secondary" icon="fas fa-arrow-left" class="me-2">
            Retour
        </x-button>
        @can('delete-campaign', $campaign)
        @if($campaign->pilgrims()->count() === 0)
        <x-button href="#" variant="outline-danger" icon="fas fa-trash"
                  onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')) { document.getElementById('delete-form').submit(); }">
            Supprimer
        </x-button>
        <form id="delete-form" method="POST" action="{{ route('campaigns.destroy', $campaign) }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        @endif
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <x-card>
            <form method="POST" action="{{ route('campaigns.update', $campaign) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Campaign Name -->
                    <div class="col-md-8">
                        <x-form.input
                            name="name"
                            label="Nom de la campagne"
                            placeholder="Ex: Hajj 2024 - Groupe A"
                            :value="old('name', $campaign->name)"
                            required
                        />
                    </div>

                    <!-- Campaign Type -->
                    <div class="col-md-4">
                        <x-form.select
                            name="type"
                            label="Type"
                            :options="[
                                'hajj' => 'Hajj',
                                'omra' => 'Omra'
                            ]"
                            :value="old('type', $campaign->type)"
                            required
                        />
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                              rows="4" placeholder="Description détaillée de la campagne...">{{ old('description', $campaign->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Start Date -->
                    <div class="col-md-6">
                        <x-form.input
                            name="start_date"
                            type="date"
                            label="Date de début"
                            :value="old('start_date', $campaign->start_date)"
                            required
                        />
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6">
                        <x-form.input
                            name="end_date"
                            type="date"
                            label="Date de fin"
                            :value="old('end_date', $campaign->end_date)"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <!-- Price -->
                    <div class="col-md-4">
                        <x-form.input
                            name="price"
                            type="number"
                            label="Prix total"
                            placeholder="50000"
                            :value="old('price', $campaign->price)"
                            required
                            append="DH"
                            step="0.01"
                            min="0"
                        />
                    </div>

                    <!-- Deposit Amount -->
                    <div class="col-md-4">
                        <x-form.input
                            name="deposit_amount"
                            type="number"
                            label="Montant d'acompte"
                            placeholder="15000"
                            :value="old('deposit_amount', $campaign->deposit_amount)"
                            append="DH"
                            step="0.01"
                            min="0"
                            help="Montant à verser lors de l'inscription"
                        />
                    </div>

                    <!-- Max Pilgrims -->
                    <div class="col-md-4">
                        <x-form.input
                            name="max_pilgrims"
                            type="number"
                            label="Nombre max de pèlerins"
                            placeholder="100"
                            :value="old('max_pilgrims', $campaign->max_pilgrims)"
                            min="1"
                            help="Laissez vide pour illimité"
                        />
                    </div>
                </div>

                <!-- Active Status -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Campagne active</strong>
                        </label>
                        <div class="form-text">Les campagnes actives sont visibles pour l'inscription</div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <x-button type="submit" variant="primary" icon="fas fa-save">
                        Enregistrer les Modifications
                    </x-button>
                    <x-button href="{{ route('campaigns.show', $campaign) }}" variant="outline-secondary">
                        Annuler
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Current Stats -->
        <x-card title="📊 Statistiques Actuelles" class="mb-4">
            <div class="row text-center">
                <div class="col-6">
                    <div class="h4 mb-0 text-primary">{{ $campaign->pilgrims()->count() }}</div>
                    <small class="text-muted">Pèlerins inscrits</small>
                </div>
                <div class="col-6">
                    <div class="h4 mb-0 text-success">
                        {{ number_format($campaign->pilgrims()->join('payments', 'pilgrims.id', '=', 'payments.pilgrim_id')->sum('payments.amount'), 0, ',', ' ') }}
                    </div>
                    <small class="text-muted">DH collectés</small>
                </div>
            </div>
            @if($campaign->pilgrims()->count() > 0)
                <hr>
                <div class="alert alert-warning small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Cette campagne a des pèlerins inscrits. Soyez prudent lors des modifications.
                </div>
            @endif
        </x-card>

        <!-- Status Card -->
        <x-card title="📋 Informations" class="mb-4">
            <div class="mb-2">
                <strong>Statut :</strong>
                <x-badge variant="{{ $campaign->is_active ? 'success' : 'secondary' }}">
                    {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                </x-badge>
            </div>
            <div class="mb-2">
                <strong>Créée le :</strong>
                {{ $campaign->created_at->format('d/m/Y à H:i') }}
            </div>
            <div class="mb-2">
                <strong>Dernière modification :</strong>
                {{ $campaign->updated_at->format('d/m/Y à H:i') }}
            </div>
            @if($campaign->created_by)
            <div>
                <strong>Créée par :</strong>
                {{ $campaign->creator->name ?? 'Utilisateur supprimé' }}
            </div>
            @endif
        </x-card>

        <!-- Warning Card -->
        @if($campaign->pilgrims()->count() > 0)
        <x-card title="⚠️ Attention" class="border-warning">
            <p class="mb-0">Cette campagne contient <strong>{{ $campaign->pilgrims()->count() }} pèlerin(s)</strong> inscrit(s).</p>
            <hr>
            <p class="small mb-0">
                Certaines modifications peuvent affecter les pèlerins existants :
            </p>
            <ul class="small mb-0 mt-2">
                <li>Changement de prix</li>
                <li>Modification des dates</li>
                <li>Réduction du nombre maximum</li>
            </ul>
        </x-card>
        @endif
    </div>
</div>
@endsection