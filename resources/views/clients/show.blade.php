@extends('layouts.app')

@section('title', $client->full_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">{{ $client->full_name }}</li>
@endsection

@section('content')
<!-- En-tête profil client moderne -->
<div class="bg-gradient-primary text-white rounded-4 mb-4 overflow-hidden position-relative">
    <div class="position-absolute top-0 end-0 opacity-10">
        <i class="fas fa-user-circle" style="font-size: 15rem;"></i>
    </div>
    <div class="container-fluid p-4 position-relative">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="position-relative">
                    <div class="avatar-xl bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center text-white shadow-lg"
                         style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 700;">
                        {{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}
                    </div>
                    @if($client->is_active)
                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2">
                            <i class="fas fa-check text-white"></i>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <h1 class="h2 mb-2 text-white fw-bold">{{ $client->full_name }}</h1>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                        <i class="fas fa-birthday-cake me-1"></i>{{ $client->age }} ans
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                        <i class="fas fa-{{ $client->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                        {{ $client->gender === 'male' ? 'Homme' : 'Femme' }}
                    </span>
                    @if($client->nationality)
                        <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                            <i class="fas fa-flag me-1"></i>{{ $client->nationality }}
                        </span>
                        @if($client->region)
                            <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $client->region }}
                            </span>
                        @endif
                    @endif
                </div>
                <div class="text-white text-opacity-75">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Client depuis {{ $client->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('clients.edit', $client) }}"
                       class="btn btn-warning btn-sm px-3">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}"
                       class="btn btn-success btn-sm px-3">
                        <i class="fas fa-plus me-2"></i>Nouveau Pèlerinage
                    </a>
                    <a href="{{ route('clients.index') }}"
                       class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <!-- Informations personnelles -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Informations personnelles
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Nom complet</small>
                                <strong class="text-dark">{{ $client->full_name }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-{{ $client->gender === 'male' ? 'mars' : 'venus' }} text-{{ $client->gender === 'male' ? 'primary' : 'danger' }} me-3"></i>
                            <div>
                                <small class="text-muted d-block">Genre</small>
                                <strong class="text-dark">{{ $client->gender === 'male' ? 'Homme' : 'Femme' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-birthday-cake text-warning me-3"></i>
                            <div>
                                <small class="text-muted d-block">Date de naissance</small>
                                <strong class="text-dark">{{ $client->date_of_birth->format('d/m/Y') }}</strong>
                                <span class="badge bg-light text-dark ms-2">{{ $client->age }} ans</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-flag text-info me-3"></i>
                            <div>
                                <small class="text-muted d-block">Nationalité</small>
                                <strong class="text-dark">{{ $client->nationality ?: 'Non renseignée' }}</strong>
                            </div>
                        </div>
                    </div>
                    @if($client->nationality === 'Cameroun' && ($client->region || $client->department))
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt text-success me-3"></i>
                            <div>
                                <small class="text-muted d-block">Région</small>
                                <strong class="text-dark">{{ $client->region ?: 'Non renseignée' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-building text-secondary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Département</small>
                                <strong class="text-dark">{{ $client->department ?: 'Non renseigné' }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-book me-2"></i>Informations de contact
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-success me-3"></i>
                            <div>
                                <small class="text-muted d-block">Téléphone</small>
                                @if($client->phone)
                                    <strong class="text-dark">
                                        <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                    </strong>
                                    <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $client->phone }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                @if($client->email)
                                    <strong class="text-dark">
                                        <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                    </strong>
                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $client->email }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($client->address)
                    <div class="col-12">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-home text-info me-3 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">Adresse</small>
                                <strong class="text-dark">{{ $client->address }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        @if($client->emergency_contact || $client->emergency_phone)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-phone-alt me-2"></i>Contact d'urgence
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user text-warning me-3"></i>
                            <div>
                                <small class="text-muted d-block">Nom du contact</small>
                                <strong class="text-dark">{{ $client->emergency_contact ?: 'Non renseigné' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-warning me-3"></i>
                            <div>
                                <small class="text-muted d-block">Téléphone d'urgence</small>
                                @if($client->emergency_phone)
                                    <strong class="text-dark">
                                        <a href="tel:{{ $client->emergency_phone }}" class="text-decoration-none">{{ $client->emergency_phone }}</a>
                                    </strong>
                                    <button class="btn btn-sm btn-outline-warning ms-2" onclick="copyToClipboard('{{ $client->emergency_phone }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Passeport -->
        @if($client->passport_number || $client->passport_expiry_date)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-passport me-2"></i>Informations de passeport
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-id-card text-info me-3"></i>
                            <div>
                                <small class="text-muted d-block">Numéro de passeport</small>
                                <strong class="text-dark">{{ $client->passport_number ?: 'Non renseigné' }}</strong>
                                @if($client->passport_number)
                                    <button class="btn btn-sm btn-outline-info ms-2" onclick="copyToClipboard('{{ $client->passport_number }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-calendar-alt text-info me-3"></i>
                            <div>
                                <small class="text-muted d-block">Date d'expiration</small>
                                @if($client->passport_expiry_date)
                                    <strong class="text-dark">{{ $client->passport_expiry_date->format('d/m/Y') }}</strong>
                                    @if($client->passport_expiry_date->isPast())
                                        <span class="badge bg-danger ms-2">Expiré</span>
                                    @elseif($client->passport_expiry_date->diffInMonths(now()) < 6)
                                        <span class="badge bg-warning ms-2">Expire bientôt</span>
                                    @else
                                        <span class="badge bg-success ms-2">Valide</span>
                                    @endif
                                @else
                                    <span class="text-muted">Non renseignée</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($client->notes)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-secondary text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sticky-note me-2"></i>Notes
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <i class="fas fa-quote-left text-secondary me-3 mt-1"></i>
                    <p class="text-dark mb-0" style="white-space: pre-line;">{{ $client->notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Statistiques -->
        <div class="card border-0 shadow-sm mb-4" data-aos="fade-left">
            <div class="card-header bg-primary text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistiques
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-check text-primary me-2"></i>
                        <span class="text-muted">Pèlerinages totaux</span>
                    </div>
                    <span class="h5 mb-0 text-primary fw-bold" data-counter="{{ $client->total_pilgrimages }}">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Pèlerinages terminés</span>
                    </div>
                    <span class="h5 mb-0 text-success fw-bold" data-counter="{{ $client->completed_pilgrimages }}">0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave text-warning me-2"></i>
                        <span class="text-muted">Total dépensé</span>
                    </div>
                    <span class="h6 mb-0 text-warning fw-bold">{{ number_format($client->total_spent, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-circle text-{{ $client->is_active ? 'success' : 'danger' }} me-2"></i>
                        <span class="text-muted">Statut</span>
                    </div>
                    <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }} px-3 py-2">
                        {{ $client->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="100">
            <div class="card-header bg-dark text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Actions rapides
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}"
                       class="btn btn-success btn-lg d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus me-2"></i>Nouveau pèlerinage
                    </a>
                    <a href="{{ route('clients.edit', $client) }}"
                       class="btn btn-warning btn-lg d-flex align-items-center justify-content-center">
                        <i class="fas fa-edit me-2"></i>Modifier le client
                    </a>
                    <button class="btn btn-outline-danger btn-lg d-flex align-items-center justify-content-center"
                            onclick="toggleClientStatus({{ $client->id }}, {{ $client->is_active ? 'false' : 'true' }})">
                        <i class="fas fa-{{ $client->is_active ? 'pause' : 'play' }} me-2"></i>
                        {{ $client->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
                </div>

    <!-- Historique des pèlerinages -->
    @if($client->pilgrims->count() > 0)
    <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm" data-aos="fade-up">
            <div class="card-header bg-gradient-primary text-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Historique des pèlerinages
                    <span class="badge bg-white text-primary ms-2">{{ $client->pilgrims->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Campagne</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Paiements</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->pilgrims as $pilgrim)
                            <tr class="table-row-hover">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-kaaba text-primary me-3"></i>
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                {{ $pilgrim->campaign ? $pilgrim->campaign->name : 'Campagne supprimée' }}
                                            </div>
                                            <small class="text-muted">
                                                @if($pilgrim->campaign && $pilgrim->campaign->departure_date)
                                                    {{ $pilgrim->campaign->departure_date->format('Y') }} - {{ $pilgrim->campaign->type }}
                                                @else
                                                    -
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pilgrim->category === 'vip' ? 'purple' : 'primary' }} px-3 py-2">
                                        <i class="fas fa-{{ $pilgrim->category === 'vip' ? 'crown' : 'user' }} me-1"></i>
                                        {{ ucfirst($pilgrim->category) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pilgrim->status === 'paid' ? 'success' : ($pilgrim->status === 'cancelled' ? 'danger' : 'warning') }} px-3 py-2">
                                        <i class="fas fa-{{ $pilgrim->status === 'paid' ? 'check-circle' : ($pilgrim->status === 'cancelled' ? 'times-circle' : 'clock') }} me-1"></i>
                                        {{ ucfirst($pilgrim->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold text-success">{{ number_format($pilgrim->paid_amount, 0, ',', ' ') }} FCFA</span>
                                        <div class="text-muted small">sur {{ number_format($pilgrim->total_amount, 0, ',', ' ') }} FCFA</div>
                                        @php
                                            $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                                        @endphp
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-{{ $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pilgrims.show', $pilgrim) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });

    // Animated counters
    function animateCounter(element) {
        const target = parseInt(element.dataset.counter);
        const duration = 1000;
        const steps = 30;
        const increment = target / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            element.textContent = Math.floor(current);

            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            }
        }, duration / steps);
    }

    // Trigger counter animations when in view
    const counters = document.querySelectorAll('[data-counter]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));

    // Copy to clipboard function
    window.copyToClipboard = function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Copié dans le presse-papier!', 'success');
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    };

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('Copié dans le presse-papier!', 'success');
        } catch (err) {
            showToast('Erreur lors de la copie', 'error');
        }
        document.body.removeChild(textArea);
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white ${bgClass} border-0 position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toast);
        });
    }

    // Toggle client status
    window.toggleClientStatus = function(clientId, isActive) {
        if (confirm(`Êtes-vous sûr de vouloir ${isActive ? 'activer' : 'désactiver'} ce client ?`)) {
            fetch(`/clients/${clientId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Une erreur s\'est produite', 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de communication', 'error');
            });
        }
    };
});
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-xl {
    width: 5rem;
    height: 5rem;
    font-size: 1.5rem;
    font-weight: bold;
}

.table-row-hover:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.badge {
    font-size: 0.75em;
    border-radius: 0.375rem;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
@endpush

@endsection