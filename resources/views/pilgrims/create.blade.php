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
                                            <div class="fw-bold">{{ number_format($selectedCampaign->price_classic, 0, ',', ' ') }} DH</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Prix VIP</small>
                                            <div class="fw-bold text-warning">{{ number_format($selectedCampaign->price_vip, 0, ',', ' ') }} DH</div>
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
                                <div class="h4 mb-0" id="category-price">0 DH</div>
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

    // Campaign data (passed from controller)
    const campaigns = @json($campaigns->keyBy('id'));

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
                        <div class="h6">${parseFloat(campaign.price_classic || 0).toLocaleString('fr-FR')} DH</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Prix VIP:</small>
                        <div class="h6 text-warning">${parseFloat(campaign.price_vip || 0).toLocaleString('fr-FR')} DH</div>
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
            categoryPrice.textContent = parseFloat(price).toLocaleString('fr-FR') + ' DH';
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