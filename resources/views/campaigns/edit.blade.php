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
                <x-form.textarea
                    name="description"
                    label="Description"
                    placeholder="Description détaillée de la campagne..."
                    :value="old('description', $campaign->description)"
                    rows="4"
                />

                <div class="row">
                    <!-- Year Hijri -->
                    <div class="col-md-6">
                        <x-form.input
                            name="year_hijri"
                            type="number"
                            label="Année Hijri"
                            placeholder="1446"
                            :value="old('year_hijri', $campaign->year_hijri)"
                            required
                            min="1400"
                        />
                    </div>

                    <!-- Year Gregorian -->
                    <div class="col-md-6">
                        <x-form.input
                            name="year_gregorian"
                            type="number"
                            label="Année Grégorienne"
                            placeholder="2024"
                            :value="old('year_gregorian', $campaign->year_gregorian)"
                            required
                            min="2020"
                        />
                    </div>
                </div>

                <div class="row">
                    <!-- Departure Date -->
                    <div class="col-md-6">
                        <x-form.input
                            name="departure_date"
                            type="date"
                            label="Date de départ"
                            :value="old('departure_date', $campaign->departure_date)"
                            required
                        />
                    </div>

                    <!-- Return Date -->
                    <div class="col-md-6">
                        <x-form.input
                            name="return_date"
                            type="date"
                            label="Date de retour"
                            :value="old('return_date', $campaign->return_date)"
                            required
                        />
                    </div>
                </div>

                <!-- Tarification par Catégorie -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">💰 Tarification par Catégorie</h5>
                    </div>
                </div>

                <div class="row">
                    <!-- Prix Classique -->
                    <div class="col-md-6">
                        <x-form.input
                            name="price_classic"
                            type="number"
                            label="Prix Classique"
                            placeholder="45000"
                            :value="old('price_classic', $campaign->price_classic)"
                            required
                            append="FCFA"
                            step="0.01"
                            min="0"
                        />
                    </div>

                    <!-- Prix VIP -->
                    <div class="col-md-6">
                        <x-form.input
                            name="price_vip"
                            type="number"
                            label="Prix VIP"
                            placeholder="65000"
                            :value="old('price_vip', $campaign->price_vip)"
                            required
                            append="FCFA"
                            step="0.01"
                            min="0"
                        />
                    </div>
                </div>

                <!-- Descriptions des Catégories -->
                <div class="row">
                    <div class="col-md-6">
                        <x-form.textarea
                            name="classic_description"
                            label="Description Classique"
                            placeholder="Services et avantages inclus dans la formule classique..."
                            :value="old('classic_description', $campaign->classic_description)"
                            rows="3"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.textarea
                            name="vip_description"
                            label="Description VIP"
                            placeholder="Services et avantages inclus dans la formule VIP..."
                            :value="old('vip_description', $campaign->vip_description)"
                            rows="3"
                        />
                    </div>
                </div>

                <!-- Statut de la Campagne -->
                <div class="mb-4">
                    <x-form.select
                        name="status"
                        label="Statut de la campagne"
                        :options="[
                            'draft' => '📝 Brouillon - En préparation',
                            'open' => '🟢 Ouverte - Inscriptions acceptées',
                            'closed' => '🔴 Fermée - Inscriptions terminées',
                            'active' => '✅ Active - En cours',
                            'completed' => '🏁 Terminée - Pèlerinage accompli',
                            'cancelled' => '❌ Annulée'
                        ]"
                        :value="old('status', $campaign->status)"
                        required
                        help="Statut actuel de la campagne"
                    />
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
                        {{ number_format($campaign->pilgrims()->join('payments', 'pilgrims.id', '=', 'payments.pilgrim_id')->where('payments.status', 'completed')->sum('payments.amount'), 0, ',', ' ') }}
                    </div>
                    <small class="text-muted">FCFA collectés</small>
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