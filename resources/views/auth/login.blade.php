@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <x-form.input
        name="email"
        type="email"
        label="Adresse e-mail"
        placeholder="admin@hajj.com"
        :value="old('email')"
        required
        autofocus
        prepend="@"
    />

    <!-- Password -->
    <x-form.input
        name="password"
        type="password"
        label="Mot de passe"
        placeholder="Entrez votre mot de passe"
        required
    />

    <!-- Remember Me -->
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">
            Se souvenir de moi
        </label>
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
        <x-button
            type="submit"
            variant="hajj-primary"
            size="lg"
            icon="fas fa-sign-in-alt"
            class="w-100">
            Se connecter
        </x-button>
    </div>
</form>

<!-- Admin Info -->
<div class="text-center mt-4 p-3 bg-light rounded">
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>
        Système réservé aux administrateurs
    </small>
</div>
@endsection