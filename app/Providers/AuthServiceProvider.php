<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define Gates for role-based permissions

        // Simplified admin permissions using is_admin
        Gate::define('manage-users', function ($user) {
            return $user->is_admin;
        });

        Gate::define('manage-settings', function ($user) {
            return $user->is_admin;
        });

        Gate::define('manage-campaigns', function ($user) {
            return $user->is_admin; // Seuls les admins peuvent gérer les campagnes
        });

        Gate::define('manage-pilgrims', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent gérer les pèlerins
        });

        Gate::define('manage-payments', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent gérer les paiements
        });

        Gate::define('manage-documents', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent gérer les documents
        });

        Gate::define('view-documents', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent voir les documents
        });

        Gate::define('view-reports', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent voir les rapports
        });

        Gate::define('export-data', function ($user) {
            return $user->is_admin; // Seuls les admins peuvent exporter
        });

        // Admin only
        Gate::define('delete-users', function ($user) {
            return $user->is_admin;
        });

        Gate::define('system-settings', function ($user) {
            return $user->is_admin;
        });

        // Define a gate to check if user can access admin panel
        Gate::define('access-admin', function ($user) {
            return true; // Tous les utilisateurs connectés peuvent accéder au panel
        });

        // Define gates for specific actions
        Gate::define('create-campaign', function ($user) {
            return $user->is_admin;
        });

        Gate::define('edit-campaign', function ($user, $campaign = null) {
            return $user->is_admin;
        });

        Gate::define('delete-campaign', function ($user, $campaign = null) {
            return $user->is_admin;
        });
    }

    /**
     * Check if user has permission through gates.
     */
    private function hasPermission($user, $permission): bool
    {
        return Gate::forUser($user)->allows($permission);
    }
}