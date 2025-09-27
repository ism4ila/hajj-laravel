@extends('layouts.app')

@section('title', 'Changer le Mot de Passe')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Mon Profil</a></li>
    <li class="breadcrumb-item active">Changer le Mot de Passe</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <x-card title="Changer le Mot de Passe" class="mb-4">
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2 text-warning"></i>
                        Sécurité du Compte
                    </h5>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </x-slot>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important :</strong> Après avoir changé votre mot de passe, vous devrez vous reconnecter.
            </div>

            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-unlock me-2"></i>
                        Mot de Passe Actuel
                    </label>
                    <input type="password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           id="current_password"
                           name="current_password"
                           required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Nouveau Mot de Passe
                    </label>
                    <div class="position-relative">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        <button type="button"
                                class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2"
                                style="border: none; background: none; z-index: 10;"
                                onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    <div class="form-text">
                        <small class="text-muted">
                            Le mot de passe doit contenir au moins 8 caractères
                        </small>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-check-double me-2"></i>
                        Confirmer le Nouveau Mot de Passe
                    </label>
                    <div class="position-relative">
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                        <button type="button"
                                class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2"
                                style="border: none; background: none; z-index: 10;"
                                onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div class="mb-4">
                    <label class="form-label">Force du Mot de Passe</label>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar"
                             id="password-strength-bar"
                             role="progressbar"
                             style="width: 0%">
                        </div>
                    </div>
                    <small id="password-strength-text" class="text-muted">Tapez votre mot de passe...</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-warning" id="submit-btn" disabled>
                        <i class="fas fa-key me-2"></i>
                        Changer le Mot de Passe
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Password Guidelines -->
    <div class="col-lg-6">
        <x-card title="Conseils de Sécurité" class="mb-4">
            <div class="security-tips">
                <div class="tip-item mb-3">
                    <div class="d-flex">
                        <div class="tip-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <strong>Utilisez des caractères variés</strong>
                            <p class="mb-0 text-muted small">Mélangez majuscules, minuscules, chiffres et symboles</p>
                        </div>
                    </div>
                </div>

                <div class="tip-item mb-3">
                    <div class="d-flex">
                        <div class="tip-icon bg-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-ruler"></i>
                        </div>
                        <div>
                            <strong>Minimum 8 caractères</strong>
                            <p class="mb-0 text-muted small">Plus c'est long, plus c'est sécurisé</p>
                        </div>
                    </div>
                </div>

                <div class="tip-item mb-3">
                    <div class="d-flex">
                        <div class="tip-icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div>
                            <strong>Évitez les mots communs</strong>
                            <p class="mb-0 text-muted small">N'utilisez pas des mots du dictionnaire ou des informations personnelles</p>
                        </div>
                    </div>
                </div>

                <div class="tip-item mb-3">
                    <div class="d-flex">
                        <div class="tip-icon bg-danger text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-user-secret"></i>
                        </div>
                        <div>
                            <strong>Gardez-le secret</strong>
                            <p class="mb-0 text-muted small">Ne partagez jamais votre mot de passe</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Recent Login Activity -->
        <x-card title="Activité Récente" class="mb-4">
            <div class="activity-item">
                <div class="d-flex align-items-center">
                    <div class="activity-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                         style="width: 30px; height: 30px; font-size: 0.8rem;">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div>
                        <strong>Dernière connexion</strong>
                        <p class="mb-0 text-muted small">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<style>
.tip-item, .activity-item {
    padding: 12px 0;
    border-bottom: 1px solid #f1f1f1;
}

.tip-item:last-child, .activity-item:last-child {
    border-bottom: none;
}

.progress {
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    transition: all 0.3s ease;
}

.security-tips .tip-icon {
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');

    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const submitBtn = document.getElementById('submit-btn');
    const confirmationField = document.getElementById('password_confirmation');

    let strength = 0;
    let feedback = [];

    // Length check
    if (password.length >= 8) strength += 25;
    else feedback.push('Au moins 8 caractères');

    // Lowercase check
    if (/[a-z]/.test(password)) strength += 25;
    else feedback.push('Une minuscule');

    // Uppercase check
    if (/[A-Z]/.test(password)) strength += 25;
    else feedback.push('Une majuscule');

    // Number or symbol check
    if (/[\d\W]/.test(password)) strength += 25;
    else feedback.push('Un chiffre ou symbole');

    // Update progress bar
    strengthBar.style.width = strength + '%';

    if (strength < 50) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Faible - ' + feedback.join(', ');
        strengthText.className = 'text-danger small';
    } else if (strength < 75) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Moyen - ' + feedback.join(', ');
        strengthText.className = 'text-warning small';
    } else if (strength < 100) {
        strengthBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Bon - ' + feedback.join(', ');
        strengthText.className = 'text-info small';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Excellent !';
        strengthText.className = 'text-success small';
    }

    // Enable/disable submit button
    const confirmationValue = confirmationField.value;
    submitBtn.disabled = strength < 75 || password !== confirmationValue || password.length === 0;
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const submitBtn = document.getElementById('submit-btn');

    if (password !== confirmation) {
        this.classList.add('is-invalid');
        submitBtn.disabled = true;
    } else {
        this.classList.remove('is-invalid');
        const strength = document.getElementById('password-strength-bar').style.width;
        submitBtn.disabled = parseInt(strength) < 75 || password.length === 0;
    }
});
</script>
@endpush