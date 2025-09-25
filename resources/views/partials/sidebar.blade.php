<!-- Sidebar -->
<nav class="sidebar bg-white position-fixed" style="width: 250px; height: 100vh; top: 0; left: 0; z-index: 1040;">
    <div class="sidebar-header p-3 border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-kaaba text-primary me-2"></i>
            {{ config('app.name', 'Hajj Management') }}
        </h5>
    </div>

    <div class="sidebar-content p-3">
        <ul class="nav nav-pills flex-column">
            <!-- Dashboard -->
            <li class="nav-item mb-1">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Campaigns -->
            <li class="nav-item mb-1">
                <a href="{{ route('campaigns.index') }}"
                   class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                    <i class="fas fa-flag me-2"></i>
                    Campagnes
                </a>
            </li>

            <!-- Pilgrims -->
            <li class="nav-item mb-1">
                <a href="{{ route('pilgrims.index') }}"
                   class="nav-link {{ request()->routeIs('pilgrims.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    Pèlerins
                </a>
            </li>

            <!-- Payments -->
            <li class="nav-item mb-1">
                <a href="{{ route('payments.index') }}"
                   class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card me-2"></i>
                    Paiements
                </a>
            </li>

            <!-- Documents are now managed per pilgrim -->

            <!-- Reports -->
            <li class="nav-item mb-1">
                <a href="{{ route('reports.index') }}"
                   class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Rapports
                </a>
            </li>

            <!-- Separator -->
            <hr class="my-3">

            <!-- User Management (Admin only) -->
            @can('manage-users')
            <li class="nav-item mb-1">
                <a href="{{ route('users.index') }}"
                   class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog me-2"></i>
                    Utilisateurs
                </a>
            </li>
            @endcan

            <!-- System Settings (Admin only) -->
            @can('manage-settings')
            <li class="nav-item mb-1">
                <a href="{{ route('settings.index') }}"
                   class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog me-2"></i>
                    Paramètres
                </a>
            </li>
            @endcan
        </ul>
    </div>

    <!-- User Profile Section -->
    <div class="sidebar-footer border-top p-3 mt-auto">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->role->name ?? 'Utilisateur' }}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="fas fa-user me-2"></i>Profil
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>