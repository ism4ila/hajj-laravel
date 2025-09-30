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
</style>
@endpush