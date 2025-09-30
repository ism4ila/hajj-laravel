@extends('layouts.app')

@section('title', 'Mon Profil')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mon Profil</li>
@endsection

@section('content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-4 mb-4">
        <x-card class="text-center h-100">
            <div class="profile-avatar mb-3">
                <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                     style="width: 100px; height: 100px; border-radius: 50%; font-size: 2.5rem; font-weight: bold;">
                    {{ substr($user->name, 0, 1) }}{{ substr(explode(' ', $user->name)[1] ?? '', 0, 1) }}
                </div>
            </div>

            <h4 class="mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-3">{{ $user->email }}</p>

            <div class="d-flex justify-content-center mb-3">
                @if($user->is_admin)
                    <span class="badge bg-danger">
                        <i class="fas fa-crown me-1"></i>
                        Administrateur
                    </span>
                @else
                    <span class="badge bg-secondary">
                        <i class="fas fa-user me-1"></i>
                        Utilisateur
                    </span>
                @endif
            </div>

            <div class="text-muted small mb-3">
                <i class="fas fa-calendar-alt me-1"></i>
                Membre depuis {{ $user->created_at->format('M Y') }}
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i>
                    Modifier le Profil
                </a>
                <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-lock me-2"></i>
                    Changer le Mot de Passe
                </a>
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#avatarModal">
                    <i class="fas fa-camera me-2"></i>
                    Changer Avatar
                </button>
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#preferencesModal">
                    <i class="fas fa-cog me-2"></i>
                    Préférences
                </button>
            </div>
        </x-card>
    </div>

    <!-- Statistics -->
    <div class="col-lg-8 mb-4">
        <x-card title="Statistiques d'Activité" class="h-100">
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <div class="stat-item">
                        <div class="stat-icon bg-primary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="mb-0 text-primary">{{ $stats['total_payments_created'] }}</h3>
                        <p class="text-muted mb-0">Paiements Créés</p>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="stat-item">
                        <div class="stat-icon bg-success text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3 class="mb-0 text-success">{{ number_format($stats['total_amount_processed'], 0, ',', ' ') }}</h3>
                        <p class="text-muted mb-0">FCFA Traités</p>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="stat-item">
                        <div class="stat-icon bg-info text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <h3 class="mb-0 text-info">{{ $stats['this_month_payments'] }}</h3>
                        <p class="text-muted mb-0">Ce Mois</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    <div class="col-12">
        <x-card title="Activité Récente" class="mb-4">
            @if($recentPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Pèlerin</th>
                                <th>Campagne</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                {{ substr($payment->pilgrim->firstname, 0, 1) }}{{ substr($payment->pilgrim->lastname, 0, 1) }}
                                            </div>
                                            {{ $payment->pilgrim->full_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $payment->pilgrim->campaign->name }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            @switch($payment->payment_method)
                                                @case('cash')
                                                    <i class="fas fa-money-bill me-1"></i>Espèces
                                                    @break
                                                @case('bank_transfer')
                                                    <i class="fas fa-university me-1"></i>Virement
                                                    @break
                                                @case('check')
                                                    <i class="fas fa-money-check me-1"></i>Chèque
                                                    @break
                                                @default
                                                    {{ $payment->payment_method }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <x-badge variant="{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-primary btn-sm">
                        Voir Tous les Paiements
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune activité récente</p>
                    <p class="text-muted small">Vos actions apparaîtront ici</p>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Account Security Info -->
<div class="row">
    <div class="col-12">
        <x-card title="Sécurité du Compte" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="security-icon bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Mot de Passe</h6>
                            <small class="text-muted">Dernière modification : {{ $user->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="security-icon bg-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>Vérifié
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Conseil de sécurité :</strong> Changez votre mot de passe régulièrement et ne le partagez jamais avec d'autres personnes.
            </div>
        </x-card>
    </div>
</div>

<!-- Avatar Change Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                         style="width: 120px; height: 120px; border-radius: 50%; font-size: 3rem; font-weight: bold;">
                        {{ substr($user->name, 0, 1) }}{{ substr(explode(' ', $user->name)[1] ?? '', 0, 1) }}
                    </div>
                </div>

                <div class="mb-3">
                    <label for="avatarUpload" class="form-label">Télécharger une nouvelle photo</label>
                    <input type="file" class="form-control" id="avatarUpload" accept="image/*">
                    <div class="form-text">Formats supportés: JPG, PNG, GIF. Taille max: 2MB</div>
                </div>

                <div class="text-center">
                    <p class="text-muted">Ou choisissez une couleur de fond:</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #007bff; border-radius: 50%;" data-color="#007bff"></button>
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #28a745; border-radius: 50%;" data-color="#28a745"></button>
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #dc3545; border-radius: 50%;" data-color="#dc3545"></button>
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #ffc107; border-radius: 50%;" data-color="#ffc107"></button>
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #6f42c1; border-radius: 50%;" data-color="#6f42c1"></button>
                        <button type="button" class="btn p-0 color-option" style="width: 40px; height: 40px; background-color: #fd7e14; border-radius: 50%;" data-color="#fd7e14"></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveAvatarChanges">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<!-- Preferences Modal -->
<div class="modal fade" id="preferencesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Préférences Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="preferencesForm">
                    <div class="mb-3">
                        <label class="form-label">Notifications</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">
                                Recevoir les notifications par email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                            <label class="form-check-label" for="pushNotifications">
                                Notifications push dans le navigateur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="paymentNotifications" checked>
                            <label class="form-check-label" for="paymentNotifications">
                                Notifications de paiements
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">Langue</label>
                        <select class="form-select" id="language">
                            <option value="fr" selected>Français</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="timezone" class="form-label">Fuseau horaire</label>
                        <select class="form-select" id="timezone">
                            <option value="Africa/Douala" selected>Afrique/Douala (WAT)</option>
                            <option value="Europe/Paris">Europe/Paris (CET)</option>
                            <option value="America/New_York">America/New_York (EST)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="currency" class="form-label">Devise préférée</label>
                        <select class="form-select" id="currency">
                            <option value="XAF" selected>Franc CFA (FCFA)</option>
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">Dollar US ($)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="theme" class="form-label">Thème</label>
                        <select class="form-select" id="theme">
                            <option value="light" selected>Clair</option>
                            <option value="dark">Sombre</option>
                            <option value="auto">Automatique</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="savePreferences">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    transition: transform 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.05);
}

.stat-item:hover .stat-icon {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stat-icon {
    transition: all 0.3s ease;
}

.security-icon {
    font-size: 1.1rem;
}

.table tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.color-option {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.color-option:hover, .color-option.selected {
    border-color: #fff;
    box-shadow: 0 0 0 2px #007bff;
}

.modal-avatar-preview {
    transition: background-color 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedColor = '#007bff';

    // Avatar color selection
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove selected class from all options
            document.querySelectorAll('.color-option').forEach(b => b.classList.remove('selected'));

            // Add selected class to clicked option
            this.classList.add('selected');

            // Store selected color
            selectedColor = this.dataset.color;

            // Update avatar preview
            const avatarPreview = document.querySelector('#avatarModal .avatar-circle');
            if (avatarPreview) {
                avatarPreview.style.backgroundColor = selectedColor;
            }
        });
    });

    // Avatar file upload preview
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarPreview = document.querySelector('#avatarModal .avatar-circle');
                if (avatarPreview) {
                    avatarPreview.style.backgroundImage = `url(${e.target.result})`;
                    avatarPreview.style.backgroundSize = 'cover';
                    avatarPreview.style.backgroundPosition = 'center';
                    avatarPreview.innerHTML = ''; // Remove initials
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Save avatar changes
    document.getElementById('saveAvatarChanges').addEventListener('click', function() {
        // Update main avatar
        const mainAvatar = document.querySelector('.profile-avatar .avatar-circle');
        if (mainAvatar) {
            const modalAvatar = document.querySelector('#avatarModal .avatar-circle');
            if (modalAvatar.style.backgroundImage) {
                // File uploaded
                mainAvatar.style.backgroundImage = modalAvatar.style.backgroundImage;
                mainAvatar.style.backgroundSize = 'cover';
                mainAvatar.style.backgroundPosition = 'center';
                mainAvatar.innerHTML = '';
            } else {
                // Color selected
                mainAvatar.style.backgroundColor = selectedColor;
            }
        }

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('avatarModal'));
        modal.hide();

        // Show success message
        showNotification('Avatar mis à jour avec succès!', 'success');
    });

    // Save preferences
    document.getElementById('savePreferences').addEventListener('click', function() {
        const preferences = {
            emailNotifications: document.getElementById('emailNotifications').checked,
            pushNotifications: document.getElementById('pushNotifications').checked,
            paymentNotifications: document.getElementById('paymentNotifications').checked,
            language: document.getElementById('language').value,
            timezone: document.getElementById('timezone').value,
            currency: document.getElementById('currency').value,
            theme: document.getElementById('theme').value
        };

        // Save to localStorage (in a real app, this would be sent to server)
        localStorage.setItem('userPreferences', JSON.stringify(preferences));

        // Apply theme immediately
        applyTheme(preferences.theme);

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('preferencesModal'));
        modal.hide();

        // Show success message
        showNotification('Préférences sauvegardées avec succès!', 'success');
    });

    // Load saved preferences
    function loadPreferences() {
        const saved = localStorage.getItem('userPreferences');
        if (saved) {
            const preferences = JSON.parse(saved);

            document.getElementById('emailNotifications').checked = preferences.emailNotifications !== false;
            document.getElementById('pushNotifications').checked = preferences.pushNotifications !== false;
            document.getElementById('paymentNotifications').checked = preferences.paymentNotifications !== false;
            document.getElementById('language').value = preferences.language || 'fr';
            document.getElementById('timezone').value = preferences.timezone || 'Africa/Douala';
            document.getElementById('currency').value = preferences.currency || 'XAF';
            document.getElementById('theme').value = preferences.theme || 'light';

            applyTheme(preferences.theme || 'light');
        }
    }

    // Apply theme
    function applyTheme(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
        } else if (theme === 'auto') {
            // Check system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
        } else {
            document.body.classList.remove('dark-theme');
        }
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }

    // Load preferences on page load
    loadPreferences();

    // Listen for system theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const saved = localStorage.getItem('userPreferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                if (preferences.theme === 'auto') {
                    applyTheme('auto');
                }
            }
        });
    }
});
</script>
@endpush