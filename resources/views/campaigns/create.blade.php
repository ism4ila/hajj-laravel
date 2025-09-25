@extends('layouts.app')

@section('title', 'Nouvelle Campagne')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item active">Nouvelle Campagne</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Créer une Nouvelle Campagne</h1>
        <p class="text-muted mb-0">Remplissez les informations de la campagne</p>
    </div>
    <x-button href="{{ route('campaigns.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
        Retour
    </x-button>
</div>

<div class="row">
    <div class="col-lg-8">
        <x-card>
            <form method="POST" action="{{ route('campaigns.store') }}">
                @csrf

                <div class="row">
                    <!-- Campaign Name -->
                    <div class="col-md-8">
                        <x-form.input
                            name="name"
                            label="Nom de la campagne"
                            placeholder="Ex: Hajj 2024 - Groupe A"
                            :value="old('name')"
                            required
                        />
                    </div>

                    <!-- Campaign Type -->
                    <div class="col-md-4">
                        <x-form.select
                            name="type"
                            label="Type"
                            :options="[
                                '' => '-- Sélectionner --',
                                'hajj' => 'Hajj',
                                'omra' => 'Omra'
                            ]"
                            :value="old('type')"
                            required
                        />
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                              rows="4" placeholder="Description détaillée de la campagne...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Year Hijri -->
                    <div class="col-md-6">
                        <x-form.input
                            name="year_hijri"
                            type="number"
                            label="Année Hijri"
                            placeholder="1446"
                            :value="old('year_hijri', 1446)"
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
                            :value="old('year_gregorian', 2024)"
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
                            :value="old('departure_date')"
                            required
                        />
                    </div>

                    <!-- Return Date -->
                    <div class="col-md-6">
                        <x-form.input
                            name="return_date"
                            type="date"
                            label="Date de retour"
                            :value="old('return_date')"
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
                            :value="old('price_classic')"
                            required
                            append="DH"
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
                            :value="old('price_vip')"
                            required
                            append="DH"
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
                            :value="old('classic_description')"
                            rows="3"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.textarea
                            name="vip_description"
                            label="Description VIP"
                            placeholder="Services et avantages inclus dans la formule VIP..."
                            :value="old('vip_description')"
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
                        ]"
                        :value="old('status', 'draft')"
                        required
                        help="Statut actuel de la campagne"
                    />
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <x-button type="submit" variant="primary" icon="fas fa-save">
                        Créer la Campagne
                    </x-button>
                    <x-button href="{{ route('campaigns.index') }}" variant="outline-secondary">
                        Annuler
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Help Card -->
        <x-card title="💡 Conseils" class="mb-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <strong>Nom :</strong> Utilisez un nom descriptif et unique
                </li>
                <li class="mb-2">
                    <strong>Dates :</strong> Vérifiez que les dates sont correctes
                </li>
                <li class="mb-2">
                    <strong>Tarifs :</strong> Classique = formule standard, VIP = formule premium
                </li>
                <li class="mb-2">
                    <strong>Descriptions :</strong> Détaillez les services inclus dans chaque formule
                </li>
                <li class="mb-2">
                    <strong>Statuts :</strong> Draft → Open → Active/Closed
                </li>
                <li>
                    <strong>Places :</strong> Pas de limite, gestion par statut
                </li>
            </ul>
        </x-card>

        <!-- Pricing Info -->
        <x-card title="📊 Différences de Formules" class="mb-4">
            <div class="small">
                <div class="mb-2">
                    <span class="badge bg-success me-2">Classique</span>
                    <span class="text-muted">Formule standard avec services essentiels</span>
                </div>
                <div class="mb-2">
                    <span class="badge bg-warning me-2">VIP</span>
                    <span class="text-muted">Formule premium avec services haut de gamme</span>
                </div>
                <hr class="my-2">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Chaque pèlerin choisit sa catégorie lors de l'inscription
                </div>
            </div>
        </x-card>

        <!-- Preview Card -->
        <x-card title="📋 Aperçu" id="preview-card" class="d-none">
            <div id="preview-content">
                <!-- Dynamic preview content will be inserted here -->
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const previewCard = document.getElementById('preview-card');
    const previewContent = document.getElementById('preview-content');

    // Form fields
    const nameField = document.querySelector('[name="name"]');
    const typeField = document.querySelector('[name="type"]');
    const priceField = document.querySelector('[name="price"]');
    const startDateField = document.querySelector('[name="start_date"]');
    const endDateField = document.querySelector('[name="end_date"]');
    const maxPilgrimsField = document.querySelector('[name="max_pilgrims"]');

    function updatePreview() {
        const name = nameField.value;
        const type = typeField.value;
        const price = priceField.value;
        const startDate = startDateField.value;
        const endDate = endDateField.value;
        const maxPilgrims = maxPilgrimsField.value;

        if (name || type || price) {
            previewCard.classList.remove('d-none');

            let html = '';
            if (name) html += `<h6 class="mb-2">${name}</h6>`;
            if (type) html += `<span class="badge bg-${type === 'hajj' ? 'primary' : 'info'} mb-2">${type.charAt(0).toUpperCase() + type.slice(1)}</span><br>`;
            if (price) html += `<strong class="text-primary">${parseFloat(price).toLocaleString('fr-FR')} DH</strong><br>`;
            if (startDate && endDate) {
                const start = new Date(startDate).toLocaleDateString('fr-FR');
                const end = new Date(endDate).toLocaleDateString('fr-FR');
                html += `<small class="text-muted">Du ${start} au ${end}</small><br>`;
            }
            if (maxPilgrims) {
                html += `<small class="text-muted">Max: ${maxPilgrims} pèlerins</small>`;
            }

            previewContent.innerHTML = html;
        } else {
            previewCard.classList.add('d-none');
        }
    }

    // Add event listeners for real-time preview
    [nameField, typeField, priceField, startDateField, endDateField, maxPilgrimsField].forEach(field => {
        if (field) {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        }
    });

    // Initial preview
    updatePreview();
});
</script>
@endpush