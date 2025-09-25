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

        // Admin permissions
        Gate::define('manage-users', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin']);
        });

        Gate::define('manage-settings', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin']);
        });

        Gate::define('manage-campaigns', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator']);
        });

        Gate::define('manage-pilgrims', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator', 'agent']);
        });

        Gate::define('manage-payments', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'accountant', 'cashier']);
        });

        Gate::define('manage-documents', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator', 'agent']);
        });

        Gate::define('view-documents', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator', 'agent', 'accountant']);
        });

        Gate::define('view-reports', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator', 'accountant']);
        });

        Gate::define('export-data', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator']);
        });

        // Super admin only
        Gate::define('delete-users', function ($user) {
            return $user->role && $user->role->name === 'super_admin';
        });

        Gate::define('system-settings', function ($user) {
            return $user->role && $user->role->name === 'super_admin';
        });

        // Define a gate to check if user can access admin panel
        Gate::define('access-admin', function ($user) {
            return $user->role && in_array($user->role->name, ['super_admin', 'admin', 'operator', 'agent', 'accountant', 'cashier']);
        });

        // Define gates for specific actions
        Gate::define('create-campaign', function ($user) {
            return Gate::forUser($user)->allows('manage-campaigns');
        });

        Gate::define('edit-campaign', function ($user, $campaign = null) {
            if (!Gate::forUser($user)->allows('manage-campaigns')) {
                return false;
            }

            // Additional logic: only campaign creator or admin can edit
            if ($campaign && !in_array($user->role->name, ['super_admin', 'admin'])) {
                return isset($campaign->created_by) && $campaign->created_by === $user->id;
            }

            return true;
        });

        Gate::define('delete-campaign', function ($user, $campaign = null) {
            if (!in_array($user->role->name, ['super_admin', 'admin'])) {
                return false;
            }

            return true;
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