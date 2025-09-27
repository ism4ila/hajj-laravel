@extends('layouts.app')

@section('title', 'Modifier le Profil')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Mon Profil</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <x-card title="Modifier le Profil" class="mb-4">
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informations Personnelles</h5>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </x-slot>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-form.input
                            label="Nom Complet"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            :error="$errors->first('name')"
                        />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-form.input
                            label="Adresse Email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            :error="$errors->first('email')"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Statut</label>
                        <div class="form-control-plaintext">
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
                        <small class="text-muted">Le statut ne peut pas être modifié</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Membre Depuis</label>
                        <div class="form-control-plaintext">
                            {{ $user->created_at->format('d/m/Y à H:i') }}
                        </div>
                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <x-card title="Actions du Compte" class="mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="action-item p-3 border rounded">
                        <div class="d-flex align-items-center">
                            <div class="action-icon bg-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Changer le Mot de Passe</h6>
                                <small class="text-muted">Modifiez votre mot de passe pour sécuriser votre compte</small>
                            </div>
                            <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning btn-sm">
                                Modifier
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="action-item p-3 border rounded">
                        <div class="d-flex align-items-center">
                            <div class="action-icon bg-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Activité</h6>
                                <small class="text-muted">Consultez votre historique d'activité</small>
                            </div>
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-info btn-sm">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Security Notice -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="alert alert-info">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-shield-alt fa-2x text-info"></i>
                </div>
                <div>
                    <h6 class="alert-heading">Sécurité de votre Compte</h6>
                    <p class="mb-2">Nous prenons la sécurité de votre compte très au sérieux. Voici quelques conseils :</p>
                    <ul class="mb-0 small">
                        <li>Utilisez un mot de passe fort et unique</li>
                        <li>Ne partagez jamais vos informations de connexion</li>
                        <li>Déconnectez-vous toujours après utilisation sur un ordinateur partagé</li>
                        <li>Signalez toute activité suspecte immédiatement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.action-item {
    transition: all 0.3s ease;
    border-color: #e3e6f0 !important;
}

.action-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #007bff !important;
}

.action-icon {
    transition: transform 0.3s ease;
}

.action-item:hover .action-icon {
    transform: scale(1.1);
}

.form-control-plaintext {
    padding: 0.375rem 0;
}
</style>
@endpush