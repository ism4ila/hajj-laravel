<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Accueil') - {{ config('app.name', 'Hajj Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(139, 157, 195, 0.9)),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path fill="%23ffffff" opacity="0.1" d="M500 100c220.9 0 400 179.1 400 400s-179.1 400-400 400S100 720.9 100 500 279.1 100 500 100z"/></svg>');
            min-height: 100vh;
            background-size: cover;
            background-position: center;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 1030;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fas fa-kaaba me-2"></i>
                {{ config('app.name', 'Hajj Management') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Connexion
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'Hajj Management System') }}</h5>
                    <p class="text-muted mb-0">Système de gestion des pèlerinages Hajj & Omra</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">© {{ date('Y') }} Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>