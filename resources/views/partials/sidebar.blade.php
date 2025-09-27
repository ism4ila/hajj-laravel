<!-- Sidebar -->
<nav class="sidebar bg-white position-fixed shadow-sm" id="sidebar" style="width: var(--sidebar-width); height: 100vh; top: 0; left: 0; z-index: 1040; transition: transform var(--transition-speed) ease;">

    <!-- Sidebar Header -->
    <div class="sidebar-header p-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fas fa-kaaba text-primary me-2"></i>
            <h5 class="mb-0 sidebar-brand-text">{{ config('app.name', 'Hajj Management') }}</h5>
        </div>

        <!-- Sidebar Toggle for Desktop -->
        <button class="btn btn-sm btn-outline-secondary d-none d-lg-inline-block" id="sidebar-toggle" title="Réduire la sidebar">
            <i class="fas fa-angle-left"></i>
        </button>

        <!-- Close Button for Mobile -->
        <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.remove('show'); document.getElementById('sidebar-overlay').classList.remove('show');">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Sidebar Content -->
    <div class="sidebar-content p-3 overflow-auto" style="height: calc(100vh - 140px);">
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

            <!-- Clients -->
            <li class="nav-item mb-1">
                <a href="{{ route('clients.index') }}"
                   class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends me-2"></i>
                    Clients
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

            <!-- System Settings (Admin seul) -->
            
            <li class="nav-item mb-1">
                <a href="{{ route('settings.index') }}"
                   class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog me-2"></i>
                    Paramètres
                </a>
            </li>
            
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
                    <div class="text-muted small">{{ auth()->user()->is_admin ? 'Administrateur' : 'Utilisateur' }}</div>
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

<!-- Sidebar Responsive Styles -->
<style>
    /* Mobile sidebar */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.show {
            transform: translateX(0);
        }
    }

    /* Sidebar collapsed state */
    .sidebar-collapsed #sidebar {
        width: var(--sidebar-collapsed-width) !important;
    }

    .sidebar-collapsed .sidebar-brand-text,
    .sidebar-collapsed .nav-link span:not(.nav-icon) {
        display: none;
    }

    .sidebar-collapsed .nav-link {
        text-align: center;
        padding: 0.5rem;
    }

    .sidebar-collapsed .sidebar-header h5 {
        display: none;
    }

    .sidebar-collapsed .sidebar-footer .dropdown-toggle .flex-grow-1 {
        display: none;
    }

    /* Sidebar animations */
    #sidebar {
        transition: width var(--transition-speed) ease, transform var(--transition-speed) ease;
    }

    .nav-link {
        transition: all 0.2s ease;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .nav-link:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }

    .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
    }

    /* Sidebar scrollbar */
    .sidebar-content::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .sidebar-content::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Responsive sidebar toggle icon */
    .sidebar-collapsed #sidebar-toggle i {
        transform: rotate(180deg);
    }

    #sidebar-toggle i {
        transition: transform var(--transition-speed) ease;
    }

    /* User dropdown in collapsed mode */
    .sidebar-collapsed .sidebar-footer .dropdown-toggle {
        justify-content: center;
    }
</style>