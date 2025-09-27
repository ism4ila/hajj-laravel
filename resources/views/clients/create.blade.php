@extends('layouts.app')

@section('title', 'Nouveau Client')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">Nouveau</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Nouveau Client</h1>
        <p class="text-muted mb-0">Créer un nouveau client dans le système</p>
    </div>
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>Informations du Client
                </h4>
            </div>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                @if(request('return_to_pilgrim'))
                    <input type="hidden" name="return_to_pilgrim" value="1">
                    @if(request('campaign_id'))
                        <input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>
                                    Vous créez un client pour l'ajouter à une campagne. Après création, vous serez redirigé vers l'inscription du pèlerin.
                                </span>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="card-body">
                    <!-- Informations personnelles -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-user me-2"></i>Informations personnelles
                        </h5>
                        <div class="row g-3">
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
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-phone me-2"></i>Contact d'urgence
                        </h5>
                        <div class="row g-3">
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
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-passport me-2"></i>Informations de voyage
                        </h5>
                        <div class="row g-3">
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
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </h5>
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
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-toggle-on me-2"></i>Statut
                        </h5>
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

                <!-- Actions -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Créer le client
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nationalityInput = document.querySelector('input[name="nationality"]');
    const cameroonFields = document.getElementById('cameroon_fields');
    const regionSelect = document.getElementById('region-select');
    const departmentSelect = document.getElementById('department-select');

    // Show/hide Cameroon fields based on nationality
    function toggleCameroonFields() {
        if (nationalityInput.value === 'Cameroun') {
            cameroonFields.style.display = '';
        } else {
            cameroonFields.style.display = 'none';
        }
    }

    // Load departments based on selected region
    function loadDepartments(region) {
        if (!region) {
            departmentSelect.innerHTML = '<option value="">Sélectionnez un département</option>';
            return;
        }

        fetch(`{{ route('clients.departments') }}?region=${region}`)
            .then(response => response.json())
            .then(data => {
                departmentSelect.innerHTML = '<option value="">Sélectionnez un département</option>';
                Object.keys(data).forEach(key => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = data[key];
                    departmentSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading departments:', error);
            });
    }

    // Event listeners
    nationalityInput.addEventListener('input', toggleCameroonFields);
    regionSelect.addEventListener('change', function() {
        loadDepartments(this.value);
    });

    // Load departments if region is already selected (for old input)
    if (regionSelect.value) {
        loadDepartments(regionSelect.value);
    }
});
</script>
@endpush
@endsection