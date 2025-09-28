@extends('layouts.app')

@section('title', 'Nouveau Client')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">Nouveau</li>
@endsection

@section('content')
<div class="create-client-page">
    <!-- Page Header responsive -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="bg-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
                    <i class="fas fa-user-plus text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h1 class="text-responsive-xl mb-1 fw-bold">Nouveau Client</h1>
                    <p class="text-muted mb-0 text-responsive-sm">
                        <i class="fas fa-info-circle me-1"></i>
                        Créer un nouveau client dans le système
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end justify-content-md-end justify-content-sm-center gap-2 flex-wrap">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    <span class="d-none d-sm-inline">Retour à la liste</span>
                    <span class="d-sm-none">Retour</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container responsive -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0 text-responsive-lg">
                            <i class="fas fa-user-plus me-2"></i>Informations du Client
                        </h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary">Étape 1/1</span>
                        </div>
                    </div>
                </div>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                @if(request('return_to_pilgrim'))
                    <input type="hidden" name="return_to_pilgrim" value="1">
                    @if(request('campaign_id'))
                        <input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-1">Création pour campagne</h6>
                                    <p class="mb-0 text-responsive-sm">
                                        Vous créez un client pour l'ajouter à une campagne. Après création, vous serez redirigé vers l'inscription du pèlerin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="card-body p-responsive-lg">
                    <!-- Progress Indicator -->
                    <div class="progress-indicator mb-4 d-none d-md-block">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="step-item active">
                                <div class="step-circle">1</div>
                                <span class="step-label">Personnel</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-circle">2</div>
                                <span class="step-label">Contact</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-circle">3</div>
                                <span class="step-label">Voyage</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-circle">4</div>
                                <span class="step-label">Finaliser</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations personnelles -->
                    <div class="form-section mb-4" data-aos="fade-up">
                        <div class="section-header mb-3">
                            <h5 class="text-primary mb-1 text-responsive-lg">
                                <i class="fas fa-user me-2"></i>Informations personnelles
                            </h5>
                            <p class="text-muted small mb-0">Renseignez les informations de base du client</p>
                        </div>
                        <div class="responsive-grid grid-2">
                            <div class="col-md-6">
                                <x-form.input
                                    name="firstname"
                                    label="Prénom *"
                                    value="{{ old('firstname') }}"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="lastname"
                                    label="Nom *"
                                    value="{{ old('lastname') }}"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.select
                                    name="gender"
                                    label="Genre *"
                                    :options="[
                                        'male' => 'Homme',
                                        'female' => 'Femme'
                                    ]"
                                    :selected="old('gender')"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="date_of_birth"
                                    label="Date de naissance *"
                                    type="date"
                                    value="{{ old('date_of_birth') }}"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="phone"
                                    label="Téléphone *"
                                    type="tel"
                                    value="{{ old('phone') }}"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="email"
                                    label="Email"
                                    type="email"
                                    value="{{ old('email') }}" />
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea name="address" id="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="form-section mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="section-header mb-3">
                            <h5 class="text-primary mb-1 text-responsive-lg">
                                <i class="fas fa-phone me-2"></i>Contact d'urgence
                            </h5>
                            <p class="text-muted small mb-0">Personne à contacter en cas d'urgence (optionnel)</p>
                        </div>
                        <div class="responsive-grid grid-2">
                            <div class="col-md-6">
                                <x-form.input
                                    name="emergency_contact"
                                    label="Nom du contact"
                                    value="{{ old('emergency_contact') }}" />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="emergency_phone"
                                    label="Téléphone du contact"
                                    type="tel"
                                    value="{{ old('emergency_phone') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Informations de voyage -->
                    <div class="form-section mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="section-header mb-3">
                            <h5 class="text-primary mb-1 text-responsive-lg">
                                <i class="fas fa-passport me-2"></i>Informations de voyage
                            </h5>
                            <p class="text-muted small mb-0">Documents et informations nécessaires pour les voyages</p>
                        </div>
                        <div class="responsive-grid grid-2">
                            <div class="col-md-6">
                                <x-form.input
                                    name="passport_number"
                                    label="Numéro de passeport"
                                    value="{{ old('passport_number') }}" />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="passport_expiry_date"
                                    label="Date d'expiration du passeport"
                                    type="date"
                                    value="{{ old('passport_expiry_date') }}" />
                            </div>

                            <div class="col-md-6">
                                <x-form.input
                                    name="nationality"
                                    label="Nationalité *"
                                    value="{{ old('nationality', 'Cameroun') }}"
                                    required />
                            </div>

                            <div class="col-md-6" id="cameroon_fields" style="{{ old('nationality', 'Cameroun') === 'Cameroun' ? '' : 'display:none;' }}">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <x-form.select
                                            name="region"
                                            label="Région"
                                            :options="$regions"
                                            :selected="old('region')"
                                            id="region-select" />
                                    </div>
                                    <div class="col-12">
                                        <x-form.select
                                            name="department"
                                            label="Département"
                                            :options="[]"
                                            :selected="old('department')"
                                            id="department-select" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-section mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="section-header mb-3">
                            <h5 class="text-primary mb-1 text-responsive-lg">
                                <i class="fas fa-sticky-note me-2"></i>Notes
                            </h5>
                            <p class="text-muted small mb-0">Informations complémentaires et remarques</p>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes personnelles</label>
                            <textarea name="notes" id="notes" rows="4" class="form-control"
                                      placeholder="Notes sur le client, préférences, remarques...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="form-section mb-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="section-header mb-3">
                            <h5 class="text-primary mb-1 text-responsive-lg">
                                <i class="fas fa-toggle-on me-2"></i>Statut
                            </h5>
                            <p class="text-muted small mb-0">Définir l'état d'activité du client</p>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">
                                Client actif
                            </label>
                            <div class="form-text">Les clients inactifs n'apparaîtront pas dans les recherches par défaut</div>
                        </div>
                    </div>
                </div>

                <!-- Actions responsive -->
                <div class="card-footer bg-light border-0">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Les champs marqués d'un * sont obligatoires</span>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-responsive">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-responsive" id="submitBtn">
                                <span class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-save me-2"></i>
                                    <span>Créer le client</span>
                                    <div class="spinner-border spinner-border-sm ms-2 d-none" role="status"></div>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('styles')
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* RESPONSIVE STYLES POUR CREATION CLIENT */
    .create-client-page {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        min-height: calc(100vh - 120px);
    }

    /* Header responsive */
    @media (max-width: 768px) {
        .create-client-page .col-lg-8,
        .create-client-page .col-lg-4 {
            text-align: center;
            margin-bottom: var(--spacing-sm);
        }
    }

    /* Card responsive */
    .create-client-page .card {
        border-radius: 1rem;
        overflow: hidden;
        transition: all var(--transition-normal);
    }

    .create-client-page .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--hajj-primary), #4dabf7);
    }

    /* Progress Indicator */
    .progress-indicator {
        margin-bottom: var(--spacing-lg);
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: var(--font-size-sm);
        transition: all var(--transition-normal);
        margin-bottom: 0.5rem;
    }

    .step-item.active .step-circle {
        background: var(--hajj-primary);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(44, 90, 160, 0.3);
    }

    .step-label {
        font-size: var(--font-size-xs);
        color: #6c757d;
        font-weight: 500;
        text-align: center;
    }

    .step-item.active .step-label {
        color: var(--hajj-primary);
        font-weight: 600;
    }

    .step-line {
        height: 2px;
        background: #e9ecef;
        flex: 1;
        margin: 0 1rem;
        align-self: center;
        margin-top: -1.5rem;
    }

    /* Form Sections */
    .form-section {
        background: rgba(248, 249, 250, 0.3);
        border-radius: 0.75rem;
        padding: var(--spacing-lg);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all var(--transition-normal);
    }

    .form-section:hover {
        background: rgba(248, 249, 250, 0.6);
        border-color: rgba(44, 90, 160, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        border-bottom: 2px solid rgba(44, 90, 160, 0.1);
        padding-bottom: var(--spacing-sm);
        margin-bottom: var(--spacing-md) !important;
    }

    /* Form Controls Responsive */
    .form-control,
    .form-select {
        min-height: 48px; /* Touch target */
        font-size: var(--font-size-sm);
        border-radius: 0.5rem;
        border: 2px solid #e9ecef;
        transition: all var(--transition-fast);
        padding: var(--spacing-sm) var(--spacing-md);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--hajj-primary);
        box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.15);
        transform: translateY(-1px);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: var(--spacing-xs);
        font-size: var(--font-size-sm);
    }

    /* Responsive Grid personnalisée */
    .responsive-grid.grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }

    @media (max-width: 768px) {
        .responsive-grid.grid-2 {
            grid-template-columns: 1fr;
            gap: var(--spacing-sm);
        }

        .progress-indicator {
            display: none !important;
        }

        .form-section {
            padding: var(--spacing-md);
            margin-bottom: var(--spacing-sm);
        }

        .section-header h5 {
            font-size: var(--font-size-base);
        }
    }

    @media (max-width: 576px) {
        .create-client-page .card-body {
            padding: var(--spacing-sm) !important;
        }

        .form-section {
            padding: var(--spacing-sm);
        }

        .form-control,
        .form-select {
            min-height: 44px;
            font-size: var(--font-size-xs);
        }
    }

    /* Actions Footer */
    .card-footer {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
        border-radius: 0 0 1rem 1rem;
        padding: var(--spacing-lg);
    }

    @media (max-width: 576px) {
        .card-footer {
            padding: var(--spacing-md);
        }

        .btn-responsive {
            width: 100%;
            min-height: 48px;
            justify-content: center;
        }
    }

    /* Boutons responsifs */
    .btn-responsive {
        min-height: 44px;
        padding: var(--spacing-sm) var(--spacing-lg);
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }

    .btn-responsive:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Checkbox et form-check */
    .form-check {
        background: rgba(248, 249, 250, 0.5);
        padding: var(--spacing-md);
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
        transition: all var(--transition-fast);
    }

    .form-check:hover {
        background: rgba(248, 249, 250, 0.8);
        border-color: var(--hajj-primary);
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.125em;
        border: 2px solid #adb5bd;
    }

    .form-check-input:checked {
        background-color: var(--hajj-primary);
        border-color: var(--hajj-primary);
    }

    .form-check-input:focus {
        border-color: var(--hajj-primary);
        box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
    }

    /* Textarea */
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    /* Loading state */
    .btn-responsive .spinner-border {
        width: 1rem;
        height: 1rem;
    }

    .loading .btn-responsive {
        pointer-events: none;
        opacity: 0.7;
    }

    /* Alert responsive */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: var(--spacing-lg);
    }

    @media (max-width: 576px) {
        .alert {
            padding: var(--spacing-md);
        }

        .alert h6 {
            font-size: var(--font-size-sm);
        }

        .alert p {
            font-size: var(--font-size-xs);
        }
    }

    /* Nationality fields animation */
    #cameroon_fields {
        transition: all var(--transition-normal);
        overflow: hidden;
    }

    #cameroon_fields.hiding {
        opacity: 0;
        max-height: 0;
        padding: 0;
        margin: 0;
    }

    #cameroon_fields.showing {
        opacity: 1;
        max-height: 500px;
    }

    /* Validation styles */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* High contrast support */
    @media (prefers-contrast: high) {
        .form-control,
        .form-select {
            border-width: 3px;
        }

        .btn-responsive {
            border-width: 2px;
        }
    }

    /* Print styles */
    @media print {
        .create-client-page .card-header,
        .create-client-page .card-footer,
        .progress-indicator {
            display: none !important;
        }

        .form-section {
            break-inside: avoid;
            background: white !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush

@push('scripts')
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser AOS
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        offset: 50
    });

    // Éléments du DOM
    const nationalityInput = document.querySelector('input[name="nationality"]');
    const cameroonFields = document.getElementById('cameroon_fields');
    const regionSelect = document.getElementById('region-select');
    const departmentSelect = document.getElementById('department-select');
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    // Animation des sections au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    // Observer les sections
    document.querySelectorAll('.form-section').forEach(section => {
        sectionObserver.observe(section);
    });

    // Animation des champs Cameroun
    function toggleCameroonFields() {
        if (nationalityInput.value === 'Cameroun') {
            cameroonFields.classList.remove('hiding');
            cameroonFields.classList.add('showing');
            cameroonFields.style.display = '';

            setTimeout(() => {
                cameroonFields.style.maxHeight = cameroonFields.scrollHeight + 'px';
                cameroonFields.style.opacity = '1';
            }, 10);
        } else {
            cameroonFields.classList.remove('showing');
            cameroonFields.classList.add('hiding');
            cameroonFields.style.maxHeight = '0';
            cameroonFields.style.opacity = '0';

            setTimeout(() => {
                cameroonFields.style.display = 'none';
            }, 300);
        }
    }

    // Charger les départements avec indicateur de chargement
    function loadDepartments(region) {
        if (!region) {
            departmentSelect.innerHTML = '<option value="">Sélectionnez un département</option>';
            return;
        }

        // Afficher l'indicateur de chargement
        departmentSelect.innerHTML = '<option value="">Chargement...</option>';
        departmentSelect.disabled = true;

        fetch(`{{ route('clients.departments') }}?region=${region}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                departmentSelect.innerHTML = '<option value="">Sélectionnez un département</option>';
                Object.keys(data).forEach(key => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = data[key];
                    departmentSelect.appendChild(option);
                });
                departmentSelect.disabled = false;

                // Animation d'apparition
                departmentSelect.style.opacity = '0.5';
                setTimeout(() => {
                    departmentSelect.style.opacity = '1';
                }, 100);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des départements:', error);
                departmentSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                departmentSelect.disabled = false;

                // Afficher un message d'erreur temporaire
                showToast('Erreur lors du chargement des départements', 'error');
            });
    }

    // Validation en temps réel
    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const fieldType = field.type;
        let isValid = true;
        let errorMessage = '';

        // Vérifier si le champ obligatoire est vide
        if (isRequired && !value) {
            isValid = false;
            errorMessage = 'Ce champ est obligatoire';
        }
        // Validation spécifique par type
        else if (value) {
            switch (fieldType) {
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'Format d\'email invalide';
                    }
                    break;
                case 'tel':
                    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
                    if (!phoneRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'Format de téléphone invalide';
                    }
                    break;
                case 'date':
                    const date = new Date(value);
                    const today = new Date();
                    if (field.name === 'date_of_birth' && date > today) {
                        isValid = false;
                        errorMessage = 'La date de naissance ne peut pas être dans le futur';
                    }
                    if (field.name === 'passport_expiry_date' && date < today) {
                        isValid = false;
                        errorMessage = 'Le passeport est expiré';
                    }
                    break;
            }
        }

        // Appliquer les styles de validation
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            removeFieldError(field);
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            showFieldError(field, errorMessage);
        }

        return isValid;
    }

    // Afficher l'erreur de champ
    function showFieldError(field, message) {
        removeFieldError(field);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = message;
        errorDiv.id = `error-${field.name}`;

        field.parentNode.appendChild(errorDiv);
    }

    // Supprimer l'erreur de champ
    function removeFieldError(field) {
        const existingError = document.getElementById(`error-${field.name}`);
        if (existingError) {
            existingError.remove();
        }
    }

    // Animation du bouton de soumission
    function setLoadingState(loading) {
        if (loading) {
            form.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.querySelector('.spinner-border').classList.remove('d-none');
            submitBtn.querySelector('span').textContent = 'Création en cours...';
        } else {
            form.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.querySelector('.spinner-border').classList.add('d-none');
            submitBtn.querySelector('span').textContent = 'Créer le client';
        }
    }

    // Toast notification
    function showToast(message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();

        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const iconClass = type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info';

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white ${bgClass} border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${iconClass} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toastContainer.removeChild(toast);
        });
    }

    // Créer le conteneur de toast
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // Event Listeners
    if (nationalityInput) {
        nationalityInput.addEventListener('input', toggleCameroonFields);
    }

    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            loadDepartments(this.value);
        });
    }

    // Validation en temps réel pour tous les champs
    const formFields = form.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        field.addEventListener('blur', () => validateField(field));
        field.addEventListener('input', () => {
            // Supprimer les styles d'erreur pendant la saisie
            if (field.classList.contains('is-invalid')) {
                field.classList.remove('is-invalid');
                removeFieldError(field);
            }
        });
    });

    // Validation du formulaire avant soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let isFormValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        // Valider tous les champs obligatoires
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isFormValid = false;
            }
        });

        if (isFormValid) {
            setLoadingState(true);

            // Simuler un délai pour l'animation
            setTimeout(() => {
                form.submit();
            }, 500);
        } else {
            // Faire défiler vers le premier champ invalide
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstInvalidField.focus();
            }

            showToast('Veuillez corriger les erreurs dans le formulaire', 'error');
        }
    });

    // Charger les départements si une région est déjà sélectionnée
    if (regionSelect && regionSelect.value) {
        loadDepartments(regionSelect.value);
    }

    // Initialiser l'état des champs Cameroun
    if (nationalityInput) {
        toggleCameroonFields();
    }

    // Auto-save drafts (optionnel)
    let draftTimeout;
    function saveDraft() {
        clearTimeout(draftTimeout);
        draftTimeout = setTimeout(() => {
            const formData = new FormData(form);
            const draftData = {};

            for (let [key, value] of formData.entries()) {
                draftData[key] = value;
            }

            localStorage.setItem('client_draft', JSON.stringify(draftData));
        }, 2000);
    }

    // Restaurer le brouillon
    function restoreDraft() {
        const draft = localStorage.getItem('client_draft');
        if (draft) {
            const draftData = JSON.parse(draft);

            Object.keys(draftData).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && !field.value) {
                    field.value = draftData[key];

                    if (field.type === 'checkbox') {
                        field.checked = draftData[key] === '1';
                    }
                }
            });

            showToast('Brouillon restauré', 'info');
        }
    }

    // Sauvegarder automatiquement les brouillons
    formFields.forEach(field => {
        field.addEventListener('input', saveDraft);
    });

    // Proposer de restaurer le brouillon au chargement
    if (localStorage.getItem('client_draft')) {
        const restoreBtn = document.createElement('button');
        restoreBtn.type = 'button';
        restoreBtn.className = 'btn btn-outline-info btn-sm';
        restoreBtn.innerHTML = '<i class="fas fa-undo me-1"></i>Restaurer le brouillon';
        restoreBtn.addEventListener('click', restoreDraft);

        const headerActions = document.querySelector('.col-lg-4 .d-flex');
        if (headerActions) {
            headerActions.appendChild(restoreBtn);
        }
    }

    // Nettoyer le brouillon après soumission réussie
    form.addEventListener('submit', function() {
        setTimeout(() => {
            localStorage.removeItem('client_draft');
        }, 1000);
    });
});
</script>
@endpush
@endsection