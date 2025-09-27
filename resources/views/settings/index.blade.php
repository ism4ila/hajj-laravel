@extends('layouts.app')

@section('title', 'Paramètres Système')

@section('breadcrumb')
    <li class="breadcrumb-item active">Paramètres</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="Paramètres du Système" class="mb-4">
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Configuration de l'Agence</h5>
                    <div>
                        <i class="fas fa-cog text-muted"></i>
                    </div>
                </div>
            </x-slot>

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($settings->isNotEmpty())
                    @foreach($settings as $category => $categorySettings)
                        <div class="settings-category mb-5">
                            <div class="category-header mb-3">
                                <h6 class="text-primary mb-1">
                                    <i class="fas fa-{{ $category === 'company' ? 'building' : ($category === 'payment' ? 'credit-card' : ($category === 'notifications' ? 'bell' : 'cogs')) }} me-2"></i>
                                    {{ match($category) {
                                        'company' => 'Informations de l\'Agence',
                                        'payment' => 'Paramètres de Paiement',
                                        'notifications' => 'Notifications',
                                        'system' => 'Système',
                                        default => ucfirst($category)
                                    } }}
                                </h6>
                                <hr class="mt-2">
                            </div>

                            <div class="row">
                                @foreach($categorySettings as $setting)
                                    <div class="col-md-6 mb-3">
                                        <div class="setting-item">
                                            <label for="setting_{{ $setting->setting_key }}" class="form-label">
                                                {{ $setting->description ?? ucfirst(str_replace('_', ' ', $setting->setting_key)) }}
                                                @if($setting->is_public)
                                                    <i class="fas fa-eye text-success ms-1" title="Visible publiquement"></i>
                                                @else
                                                    <i class="fas fa-lock text-warning ms-1" title="Paramètre interne"></i>
                                                @endif
                                            </label>

                                            @if($setting->setting_type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="setting_{{ $setting->setting_key }}"
                                                           name="settings[{{ $setting->setting_key }}]"
                                                           value="1"
                                                           {{ $setting->setting_value ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="setting_{{ $setting->setting_key }}">
                                                        {{ $setting->setting_value ? 'Activé' : 'Désactivé' }}
                                                    </label>
                                                </div>
                                            @elseif($setting->setting_type === 'number')
                                                <input type="number"
                                                       class="form-control @error('settings.'.$setting->setting_key) is-invalid @enderror"
                                                       id="setting_{{ $setting->setting_key }}"
                                                       name="settings[{{ $setting->setting_key }}]"
                                                       value="{{ old('settings.'.$setting->setting_key, $setting->setting_value) }}"
                                                       step="any">
                                            @elseif($setting->setting_key === 'system_timezone')
                                                <select class="form-select @error('settings.'.$setting->setting_key) is-invalid @enderror"
                                                        id="setting_{{ $setting->setting_key }}"
                                                        name="settings[{{ $setting->setting_key }}]">
                                                    <option value="Africa/Douala" {{ $setting->setting_value === 'Africa/Douala' ? 'selected' : '' }}>Douala, Cameroun (GMT+1)</option>
                                                    <option value="Africa/Casablanca" {{ $setting->setting_value === 'Africa/Casablanca' ? 'selected' : '' }}>Casablanca (GMT+1)</option>
                                                    <option value="Europe/Paris" {{ $setting->setting_value === 'Europe/Paris' ? 'selected' : '' }}>Paris (CET)</option>
                                                    <option value="UTC" {{ $setting->setting_value === 'UTC' ? 'selected' : '' }}>UTC</option>
                                                </select>
                                            @elseif($setting->setting_key === 'default_currency')
                                                <select class="form-select @error('settings.'.$setting->setting_key) is-invalid @enderror"
                                                        id="setting_{{ $setting->setting_key }}"
                                                        name="settings[{{ $setting->setting_key }}]">
                                                    <option value="FCFA" {{ $setting->setting_value === 'FCFA' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                                                    <option value="EUR" {{ $setting->setting_value === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                                    <option value="USD" {{ $setting->setting_value === 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                                                </select>
                                            @elseif($setting->setting_key === 'company_logo')
                                                <div class="logo-upload-section">
                                                    @if($setting->setting_value)
                                                        <div class="current-logo mb-3">
                                                            <label class="form-label">Logo actuel :</label>
                                                            <div class="logo-preview border rounded p-3 bg-light">
                                                                <img src="{{ asset('storage/logos/' . $setting->setting_value) }}"
                                                                     alt="Logo actuel"
                                                                     style="max-height: 100px; max-width: 200px;">
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mb-2">
                                                        <label for="logo_upload" class="form-label">
                                                            {{ $setting->setting_value ? 'Changer le logo' : 'Télécharger un logo' }}
                                                        </label>
                                                        <input type="file"
                                                               class="form-control @error('company_logo') is-invalid @enderror"
                                                               id="logo_upload"
                                                               name="company_logo"
                                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                                        <div class="form-text">
                                                            Formats acceptés: JPG, PNG, GIF. Taille recommandée: 300x100px
                                                        </div>
                                                    </div>

                                                    @if($setting->setting_value)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   id="remove_logo" name="remove_logo" value="1">
                                                            <label class="form-check-label text-danger" for="remove_logo">
                                                                Supprimer le logo actuel
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                @if(Str::contains($setting->setting_key, ['address', 'terms']))
                                                    <textarea class="form-control @error('settings.'.$setting->setting_key) is-invalid @enderror"
                                                              id="setting_{{ $setting->setting_key }}"
                                                              name="settings[{{ $setting->setting_key }}]"
                                                              rows="3">{{ old('settings.'.$setting->setting_key, $setting->setting_value) }}</textarea>
                                                @else
                                                    <input type="{{ Str::contains($setting->setting_key, 'email') ? 'email' : (Str::contains($setting->setting_key, ['phone', 'tel']) ? 'tel' : 'text') }}"
                                                           class="form-control @error('settings.'.$setting->setting_key) is-invalid @enderror"
                                                           id="setting_{{ $setting->setting_key }}"
                                                           name="settings[{{ $setting->setting_key }}]"
                                                           value="{{ old('settings.'.$setting->setting_key, $setting->setting_value) }}">
                                                @endif
                                            @endif

                                            @error('settings.'.$setting->setting_key)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer les Paramètres
                        </button>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun paramètre configuré</p>
                        <small class="text-muted">Les paramètres par défaut seront créés automatiquement</small>
                    </div>
                @endif
            </form>
        </x-card>
    </div>
</div>

<!-- Settings Help Modal -->
<div class="modal fade" id="settingsHelpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aide - Paramètres</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="companyHelp">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompany">
                                <i class="fas fa-building me-2"></i>
                                Informations de l'Agence
                            </button>
                        </h2>
                        <div id="collapseCompany" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Ces informations apparaissent sur les reçus, factures et documents officiels.
                                <ul class="mt-2">
                                    <li><strong>Nom :</strong> Nom officiel de votre agence</li>
                                    <li><strong>Adresse :</strong> Adresse complète</li>
                                    <li><strong>Contact :</strong> Téléphone et email principaux</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="paymentHelp">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayment">
                                <i class="fas fa-credit-card me-2"></i>
                                Paramètres de Paiement
                            </button>
                        </h2>
                        <div id="collapsePayment" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Configuration des règles de paiement et facturation.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.settings-category {
    border-left: 4px solid #007bff;
    padding-left: 20px;
}

.category-header h6 {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.setting-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    height: 100%;
}

.setting-item label {
    font-weight: 500;
    color: #495057;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.fa-eye {
    font-size: 0.8em;
}

.fa-lock {
    font-size: 0.8em;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-update switch labels
document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const label = this.parentNode.querySelector('.form-check-label');
        label.textContent = this.checked ? 'Activé' : 'Désactivé';
    });
});

// Show help modal
function showHelp() {
    const modal = new bootstrap.Modal(document.getElementById('settingsHelpModal'));
    modal.show();
}
</script>
@endpush