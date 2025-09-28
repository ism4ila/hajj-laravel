@extends('layouts.app')

@section('title', 'Dashboard Responsive')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
    /* Styles spécifiques au dashboard responsive */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }

    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
        padding: var(--spacing-sm);
    }

    .timeline-item {
        display: flex;
        gap: var(--spacing-sm);
        padding: var(--spacing-sm) 0;
        border-bottom: 1px solid #e9ecef;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quick-action-btn {
        background: var(--hajj-primary);
        color: white;
        border: none;
        border-radius: 0.75rem;
        padding: var(--spacing-md);
        text-align: center;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-xs);
        transition: all var(--transition-fast);
        min-height: 120px;
        justify-content: center;
    }

    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        background: #1e3d6f;
        color: white;
        text-decoration: none;
    }

    .quick-action-btn i {
        font-size: 2rem;
        margin-bottom: var(--spacing-xs);
    }

    .chart-container {
        position: relative;
        height: 300px;
        background: #f8f9fa;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-sm);
        }

        .quick-stats {
            grid-template-columns: 1fr;
            gap: var(--spacing-sm);
        }

        .chart-container {
            height: 250px;
        }

        .quick-action-btn {
            min-height: 100px;
            padding: var(--spacing-sm);
        }

        .quick-action-btn i {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .activity-timeline {
            max-height: 300px;
        }

        .timeline-item {
            flex-direction: column;
            text-align: center;
        }

        .timeline-icon {
            align-self: center;
        }
    }
</style>
@endpush

@section('content')
<div class="animate-fade-in">
    <!-- Header responsive -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="text-responsive-xxl mb-1">Tableau de Bord</h1>
            <p class="text-muted text-responsive-sm mb-0">Vue d'ensemble de votre système de gestion Hajj</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download me-1"></i>
                <span class="hide-mobile">Exporter</span>
            </button>
            <button class="btn btn-hajj-primary btn-sm">
                <i class="fas fa-sync me-1"></i>
                <span class="hide-mobile">Actualiser</span>
            </button>
        </div>
    </div>

    <!-- Stats rapides responsive -->
    <div class="quick-stats">
        <div class="card stat-card animate-slide-up">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Total Pèlerins</div>
                        <div class="text-responsive-xl mb-0 font-weight-bold text-white">
                            {{ number_format($stats['total_pilgrims'] ?? 1247) }}
                        </div>
                        <div class="text-white-75 small mt-1">
                            <i class="fas fa-arrow-up me-1"></i>+12% ce mois
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-success text-white animate-slide-up" style="animation-delay: 0.1s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Campagnes Actives</div>
                        <div class="text-responsive-xl mb-0 font-weight-bold text-white">
                            {{ $stats['active_campaigns'] ?? 15 }}
                        </div>
                        <div class="text-white-75 small mt-1">
                            <i class="fas fa-check me-1"></i>3 complètes
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-flag fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-info text-white animate-slide-up" style="animation-delay: 0.2s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Total Paiements</div>
                        <div class="text-responsive-lg mb-0 font-weight-bold text-white">
                            {{ number_format($stats['total_payments'] ?? 85000000, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="text-white-75 small mt-1">
                            <i class="fas fa-chart-line me-1"></i>+8% ce mois
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-warning text-dark animate-slide-up" style="animation-delay: 0.3s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75">Documents En Attente</div>
                        <div class="text-responsive-xl mb-0 font-weight-bold">
                            {{ $stats['pending_documents'] ?? 24 }}
                        </div>
                        <div class="small opacity-75 mt-1">
                            <i class="fas fa-clock me-1"></i>À traiter
                        </div>
                    </div>
                    <div class="opacity-50">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal en grille responsive -->
    <div class="dashboard-grid">
        <!-- Actions rapides -->
        <div class="card animate-slide-up" style="animation-delay: 0.4s;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Actions Rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('pilgrims.create') }}" class="quick-action-btn">
                            <i class="fas fa-user-plus"></i>
                            <span class="text-responsive-sm fw-semibold">Nouveau Pèlerin</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('payments.create') }}" class="quick-action-btn">
                            <i class="fas fa-credit-card"></i>
                            <span class="text-responsive-sm fw-semibold">Nouveau Paiement</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('campaigns.create') }}" class="quick-action-btn">
                            <i class="fas fa-flag"></i>
                            <span class="text-responsive-sm fw-semibold">Nouvelle Campagne</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('reports.index') }}" class="quick-action-btn">
                            <i class="fas fa-chart-bar"></i>
                            <span class="text-responsive-sm fw-semibold">Rapports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activité récente -->
        <div class="card animate-slide-up" style="animation-delay: 0.5s;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock text-primary me-2"></i>
                    Activité Récente
                </h5>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="hide-mobile ms-1">Voir tout</span>
                </button>
            </div>
            <div class="card-body p-0">
                <div class="activity-timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success text-white">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-responsive-sm">Nouveau pèlerin inscrit</div>
                            <div class="text-muted small">Ahmed Ben Ali - Hajj 2024</div>
                            <div class="text-muted small">Il y a 5 minutes</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon bg-info text-white">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-responsive-sm">Paiement reçu</div>
                            <div class="text-muted small">500,000 FCFA - Fatima Zahra</div>
                            <div class="text-muted small">Il y a 15 minutes</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon bg-warning text-dark">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-responsive-sm">Document uploadé</div>
                            <div class="text-muted small">Passeport - Mamadou BA</div>
                            <div class="text-muted small">Il y a 1 heure</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary text-white">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-responsive-sm">Campagne mise à jour</div>
                            <div class="text-muted small">Omra Ramadan 2024 - Prix modifiés</div>
                            <div class="text-muted small">Il y a 2 heures</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon bg-danger text-white">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-responsive-sm">Document manquant</div>
                            <div class="text-muted small">Visa requis - Aissatou DIOP</div>
                            <div class="text-muted small">Il y a 3 heures</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique responsive -->
        <div class="card animate-slide-up" style="animation-delay: 0.6s;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line text-success me-2"></i>
                    Évolution des Paiements
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div class="text-center">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Graphique interactif des paiements</p>
                        <small class="text-muted">
                            Intégration Chart.js recommandée
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et notifications -->
        <div class="card animate-slide-up" style="animation-delay: 0.7s;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bell text-warning me-2"></i>
                    Alertes Important
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">Documents en attente</div>
                        <div class="small">24 documents nécessitent votre attention</div>
                    </div>
                    <button class="btn btn-sm btn-warning">Voir</button>
                </div>

                <div class="alert alert-info d-flex align-items-center mb-3">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">Départ proche</div>
                        <div class="small">Campagne Hajj 2024 dans 30 jours</div>
                    </div>
                    <button class="btn btn-sm btn-info">Détails</button>
                </div>

                <div class="alert alert-success d-flex align-items-center mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">Système à jour</div>
                        <div class="small">Toutes les fonctionnalités opérationnelles</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Raccourcis rapides -->
        <div class="card animate-slide-up" style="animation-delay: 0.8s;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bookmark text-info me-2"></i>
                    Raccourcis
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('pilgrims.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-users text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Gérer les Pèlerins</div>
                            <small class="text-muted">Ajouter, modifier, voir statuts</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('payments.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-credit-card text-success me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Gestion Paiements</div>
                            <small class="text-muted">Encaissements et reçus</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('campaigns.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-flag text-warning me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Campagnes Hajj/Omra</div>
                            <small class="text-muted">Organisation et planification</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-chart-bar text-info me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Rapports & Analytics</div>
                            <small class="text-muted">Statistiques détaillées</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Résumé financier -->
        <div class="card animate-slide-up" style="animation-delay: 0.9s;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-coins text-warning me-2"></i>
                    Résumé Financier
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-responsive-lg fw-bold text-success">₣45M</div>
                            <div class="small text-muted">Recettes ce mois</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-responsive-lg fw-bold text-info">₣12M</div>
                            <div class="small text-muted">En attente</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 78%"></div>
                        </div>
                        <small class="text-muted">78% des objectifs atteints</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des statistiques au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer tous les éléments animés
    document.querySelectorAll('.animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });

    // Auto-refresh des données toutes les 30 secondes (optionnel)
    setInterval(() => {
        console.log('🔄 Rafraîchissement automatique des données dashboard');
        // Ici vous pouvez ajouter une requête AJAX pour mettre à jour les stats
    }, 30000);

    // Responsive helpers
    function handleResponsiveChanges() {
        const isMobile = window.innerWidth <= 768;
        const cards = document.querySelectorAll('.quick-action-btn');

        cards.forEach(card => {
            if (isMobile) {
                card.style.minHeight = '80px';
            } else {
                card.style.minHeight = '120px';
            }
        });
    }

    // Écouteur de redimensionnement
    window.addEventListener('resize', handleResponsiveChanges);
    handleResponsiveChanges(); // Appel initial

    console.log('📱 Dashboard responsive initialisé');
});
</script>
@endpush