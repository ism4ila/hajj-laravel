@extends('layouts.guest')

@section('title', 'Accueil')

@section('content')
<div class="hero-section d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-8">
                <!-- Hero Content -->
                <div class="mb-5">
                    <div class="brand-logo mx-auto mb-4" style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                        <i class="fas fa-kaaba"></i>
                    </div>

                    <h1 class="display-4 fw-bold mb-4">
                        {{ config('app.name', 'Hajj Management System') }}
                    </h1>

                    <p class="lead mb-5">
                        Système complet de gestion des pèlerinages Hajj & Omra.
                    </p>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Se connecter
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Accéder au Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h2 class="fw-bold">Fonctionnalités Principales</h2>
                <p class="text-muted">Un système complet pour la gestion de vos pèlerinages</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Campaign Management -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-flag fa-lg"></i>
                        </div>
                        <h5 class="card-title">Gestion des Campagnes</h5>
                        <p class="card-text text-muted">Créez et gérez vos campagnes Hajj et Omra.</p>
                    </div>
                </div>
            </div>

            <!-- Pilgrim Management -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <h5 class="card-title">Gestion des Pèlerins</h5>
                        <p class="card-text text-muted">Suivez tous vos pèlerins et leurs informations.</p>
                    </div>
                </div>
            </div>

            <!-- Payment Management -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-credit-card fa-lg"></i>
                        </div>
                        <h5 class="card-title">Paiements</h5>
                        <p class="card-text text-muted">Gérez les paiements et générez des reçus.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection