<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Hajj Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content" style="margin-left: 250px;">
        <!-- Top Header -->
        @include('partials.header')

        <!-- Flash Messages -->
        @include('partials.alerts')

        <!-- Page Content -->
        <main class="container-fluid p-4">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>