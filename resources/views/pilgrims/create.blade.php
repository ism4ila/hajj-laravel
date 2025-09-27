@extends('layouts.app')

@section('title', 'Nouveau Pèlerin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.index') }}">Pèlerins</a></li>
    <li class="breadcrumb-item active">Nouveau Pèlerin</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Inscrire un Nouveau Pèlerin</h1>
        <p class="text-muted mb-0">Remplissez les informations du pèlerin</p>
    </div>
    <x-button href="{{ route('pilgrims.index') }}" variant="outline-secondary" icon="fas fa-arrow-left">
        Retour
    </x-button>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('pilgrims.store') }}">
            @csrf

            <!-- Client Selection -->
            <x-card title="👤 Sélection du Client" class="mb-4">
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="client_option" id="existing_client" value="existing" checked>
                        <label class="form-check-label" for="existing_client">
                            Client existant
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="client_option" id="new_client" value="new">
                        <label class="form-check-label" for="new_client">
                            Nouveau client
                        </label>
                    </div>
                </div>

                <!-- Existing Client Selection -->
                <div id="existing_client_section">
                    <div class="row">
                        <div class="col-md-9">
                            <label class="form-label">Rechercher un client</label>
                            <select name="client_id" id="client_select" class="form-select">
                                <option value="">Tapez pour rechercher un client...</option>
                                @if(isset($selectedClient))
                                    <option value="{{ $selectedClient->id }}" selected>
                                        {{ $selectedClient->full_name }} - {{ $selectedClient->phone }}
                                    </option>
                                @endif
                            </select>
                            <div class="form-text">Recherche par nom, téléphone ou email</div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            @php
                                $createClientUrl = route('clients.create') . '?return_to_pilgrim=1';
                                if(isset($selectedCampaign)) {
                                    $createClientUrl .= '&campaign_id=' . $selectedCampaign->id;
                                }
                            @endphp
                            <a href="{{ $createClientUrl }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-1"></i>
                                Nouveau Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- New Client Checkbox -->
                <div id="new_client_section" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Un nouveau client sera créé avec les informations saisies ci-dessous.
                    </div>
                    <input type="hidden" name="create_client" value="0" id="create_client_input">
                </div>
            </x-card>

            <!-- Campaign Selection -->
            <x-card title="🏕️ Campagne & Catégorie" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <x-form.select
                            name="campaign_id"
                            label="Campagne"
                            :options="['' => '-- Sélectionner une campagne --'] + $campaigns->pluck('name', 'id')->toArray()"
                            :value="$selectedCampaign?->id ?? old('campaign_id')"
                            required
                            help="Choisissez la campagne pour ce pèlerin"
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.select
                            name="category"
                            label="Catégorie"
                            :options="[
                                '' => '-- Sélectionner --',
                                'classic' => '🥉 Classique',
                                'vip' => '🥇 VIP'
                            ]"
                            :value="old('category')"
                            required
                            help="Choisissez la formule désirée"
                            id="category-select"
                        />
                    </div>
                </div>

                @if($selectedCampaign)
                    <div class="alert alert-info mt-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $selectedCampaign->name }}</strong>
                                <x-badge variant="{{ $selectedCampaign->type === 'hajj' ? 'primary' : 'info' }}" class="ms-2">
                                    {{ ucfirst($selectedCampaign->type) }}
                                </x-badge>
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Prix Classique</small>
                                            <div class="fw-bold">{{ number_format($selectedCampaign->price_classic, 0, ',', ' ') }} FCFA</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Prix VIP</small>
                                            <div class="fw-bold text-warning">{{ number_format($selectedCampaign->price_vip, 0, ',', ' ') }} FCFA</div>
                                        </div>
                                    </div>
                                </div>
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
                            :value="old('firstname')"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="lastname"
                            label="Nom"
                            placeholder="Nom de famille"
                            :value="old('lastname')"
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
                            :value="old('gender')"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="date_of_birth"
                            type="date"
                            label="Date de naissance"
                            :value="old('date_of_birth')"
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
                            :value="old('email')"
                            prepend="envelope"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="phone"
                            label="Téléphone"
                            placeholder="+212 6XX XX XX XX"
                            :value="old('phone')"
                            prepend="phone"
                        />
                    </div>
                </div>

                <x-form.textarea
                    name="address"
                    label="Adresse"
                    placeholder="Adresse complète..."
                    :value="old('address')"
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
                            :value="old('emergency_contact')"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="emergency_phone"
                            label="Téléphone d'urgence"
                            placeholder="+212 6XX XX XX XX"
                            :value="old('emergency_phone')"
                            required
                            prepend="phone"
                        />
                    </div>
                </div>
            </x-card>

            <!-- Additional Client Information (for new clients only) -->
            <x-card title="🛂 Informations Supplémentaires" class="mb-4" id="additional_client_info" style="display: none;">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Ces informations seront utilisées pour créer le profil client.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="passport_number"
                            label="Numéro de passeport"
                            placeholder="Numéro du passeport"
                            :value="old('passport_number')"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="passport_expiry_date"
                            type="date"
                            label="Date d'expiration du passeport"
                            :value="old('passport_expiry_date')"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="nationality"
                            label="Nationalité"
                            placeholder="Ex: Marocaine"
                            :value="old('nationality')"
                        />
                    </div>
                </div>
            </x-card>

            <!-- Pricing Information -->
            <div id="pricing-info" class="d-none">
                <x-card title="💰 Tarification" class="mb-4">
                    <div class="alert alert-success mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong id="selected-category">Catégorie sélectionnée</strong>
                                <div id="category-description" class="small text-muted mt-1"></div>
                            </div>
                            <div class="text-end">
                                <div class="h4 mb-0" id="category-price">0 FCFA</div>
                                <small class="text-muted">Prix total</small>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <x-button type="submit" variant="primary" icon="fas fa-user-plus">
                    Inscrire le Pèlerin
                </x-button>
                <x-button href="{{ route('pilgrims.index') }}" variant="outline-secondary">
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
                    <strong>Campagne :</strong> Sélectionnez la campagne appropriée
                </li>
                <li class="mb-2">
                    <strong>Catégorie :</strong> Classique = services standard, VIP = services premium
                </li>
                <li class="mb-2">
                    <strong>Informations :</strong> Vérifiez l'exactitude des données
                </li>
                <li class="mb-2">
                    <strong>Contact d'urgence :</strong> Obligatoire pour tous les pèlerins
                </li>
                <li>
                    <strong>Paiement :</strong> Le montant sera calculé automatiquement selon la catégorie
                </li>
            </ul>
        </x-card>

        <!-- Category Comparison -->
        <x-card title="📊 Comparaison des Formules" class="mb-4">
            <div class="small">
                <div class="mb-3">
                    <span class="badge bg-success me-2">Classique</span>
                    <span class="text-muted">Formule standard avec services essentiels</span>
                </div>
                <div class="mb-3">
                    <span class="badge bg-warning me-2">VIP</span>
                    <span class="text-muted">Formule premium avec services haut de gamme</span>
                </div>
                <hr class="my-2">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Le prix sera affiché selon la catégorie sélectionnée
                </div>
            </div>
        </x-card>

        <!-- Selected Campaign Info -->
        <div id="campaign-info" class="d-none">
            <x-card title="📋 Informations Campagne" class="mb-4">
                <div id="campaign-details">
                    <!-- Dynamic campaign details will be inserted here -->
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const campaignSelect = document.querySelector('[name="campaign_id"]');
    const categorySelect = document.querySelector('[name="category"]');
    const campaignInfo = document.getElementById('campaign-info');
    const campaignDetails = document.getElementById('campaign-details');
    const pricingInfo = document.getElementById('pricing-info');
    const selectedCategoryEl = document.getElementById('selected-category');
    const categoryDescription = document.getElementById('category-description');
    const categoryPrice = document.getElementById('category-price');

    // Client selection elements
    const existingClientRadio = document.getElementById('existing_client');
    const newClientRadio = document.getElementById('new_client');
    const existingClientSection = document.getElementById('existing_client_section');
    const newClientSection = document.getElementById('new_client_section');
    const additionalClientInfo = document.getElementById('additional_client_info');
    const createClientInput = document.getElementById('create_client_input');
    const clientSelect = document.getElementById('client_select');

    // Campaign data (passed from controller)
    const campaigns = @json($campaigns->keyBy('id'));

    // Client selection functionality
    function toggleClientSelection() {
        if (existingClientRadio.checked) {
            existingClientSection.style.display = 'block';
            newClientSection.style.display = 'none';
            additionalClientInfo.style.display = 'none';
            createClientInput.value = '0';
        } else if (newClientRadio.checked) {
            existingClientSection.style.display = 'none';
            newClientSection.style.display = 'block';
            additionalClientInfo.style.display = 'block';
            createClientInput.value = '1';
            clientSelect.value = '';
        }
    }

    // Initialize Select2 for client search
    $(clientSelect).select2({
        placeholder: 'Rechercher un client...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("clients.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(client) {
                        return {
                            id: client.id,
                            text: client.firstname + ' ' + client.lastname + ' - ' + (client.phone || 'Pas de téléphone'),
                            client: client
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Fill form when client is selected
    $(clientSelect).on('select2:select', function (e) {
        const client = e.params.data.client;
        if (client) {
            // Fill form fields with client data
            document.querySelector('[name="firstname"]').value = client.firstname || '';
            document.querySelector('[name="lastname"]').value = client.lastname || '';
            document.querySelector('[name="gender"]').value = client.gender || '';
            document.querySelector('[name="date_of_birth"]').value = client.date_of_birth || '';
            document.querySelector('[name="phone"]').value = client.phone || '';
            document.querySelector('[name="email"]').value = client.email || '';
            document.querySelector('[name="address"]').value = client.address || '';
            document.querySelector('[name="emergency_contact"]').value = client.emergency_contact || '';
            document.querySelector('[name="emergency_phone"]').value = client.emergency_phone || '';
        }
    });

    // Clear form when client is deselected
    $(clientSelect).on('select2:clear', function (e) {
        // Clear form fields
        document.querySelector('[name="firstname"]').value = '';
        document.querySelector('[name="lastname"]').value = '';
        document.querySelector('[name="gender"]').value = '';
        document.querySelector('[name="date_of_birth"]').value = '';
        document.querySelector('[name="phone"]').value = '';
        document.querySelector('[name="email"]').value = '';
        document.querySelector('[name="address"]').value = '';
        document.querySelector('[name="emergency_contact"]').value = '';
        document.querySelector('[name="emergency_phone"]').value = '';
    });

    // Event listeners for client selection
    existingClientRadio.addEventListener('change', toggleClientSelection);
    newClientRadio.addEventListener('change', toggleClientSelection);

    // Initialize client selection
    toggleClientSelection();

    function updateCampaignInfo() {
        const selectedId = campaignSelect.value;
        if (selectedId && campaigns[selectedId]) {
            const campaign = campaigns[selectedId];

            // Show campaign info
            const departureDate = new Date(campaign.departure_date).toLocaleDateString('fr-FR');
            const returnDate = new Date(campaign.return_date).toLocaleDateString('fr-FR');

            let html = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <strong>${campaign.name}</strong>
                        <span class="badge bg-${campaign.type === 'hajj' ? 'primary' : 'info'}">${campaign.type.charAt(0).toUpperCase() + campaign.type.slice(1)}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">Prix Classique:</small>
                        <div class="h6">${parseFloat(campaign.price_classic || 0).toLocaleString('fr-FR')} FCFA</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Prix VIP:</small>
                        <div class="h6 text-warning">${parseFloat(campaign.price_vip || 0).toLocaleString('fr-FR')} FCFA</div>
                    </div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Dates:</small>
                    <div>Du ${departureDate} au ${returnDate}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Années:</small>
                    <div>${campaign.year_hijri}H / ${campaign.year_gregorian}G</div>
                </div>
            `;

            campaignDetails.innerHTML = html;
            campaignInfo.classList.remove('d-none');

            // Update category pricing
            updateCategoryPricing();
        } else {
            campaignInfo.classList.add('d-none');
            pricingInfo.classList.add('d-none');
        }
    }

    function updateCategoryPricing() {
        const selectedCampaignId = campaignSelect.value;
        const selectedCategory = categorySelect.value;

        if (selectedCampaignId && selectedCategory && campaigns[selectedCampaignId]) {
            const campaign = campaigns[selectedCampaignId];
            let price, categoryName, description;

            if (selectedCategory === 'classic') {
                price = campaign.price_classic || 0;
                categoryName = '🥉 Formule Classique';
                description = campaign.classic_description || 'Services standards avec l\'essentiel pour un pèlerinage réussi';
            } else if (selectedCategory === 'vip') {
                price = campaign.price_vip || 0;
                categoryName = '🥇 Formule VIP';
                description = campaign.vip_description || 'Services premium avec prestations haut de gamme';
            }

            selectedCategoryEl.textContent = categoryName;
            categoryDescription.textContent = description;
            categoryPrice.textContent = parseFloat(price).toLocaleString('fr-FR') + ' FCFA';
            pricingInfo.classList.remove('d-none');
        } else {
            pricingInfo.classList.add('d-none');
        }
    }

    // Event listeners
    campaignSelect.addEventListener('change', function() {
        updateCampaignInfo();
        // Reset category when campaign changes
        categorySelect.value = '';
        pricingInfo.classList.add('d-none');
    });

    categorySelect.addEventListener('change', updateCategoryPricing);

    // Initial update
    updateCampaignInfo();
});
</script>
@endpush