<!-- Header -->
<header class="bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <!-- Left side with mobile menu and breadcrumbs -->
            <div class="col-md-6 d-flex align-items-center">
                <!-- Mobile Menu Toggle -->
                <button class="btn btn-outline-secondary me-3 mobile-menu-toggle" id="mobile-menu-toggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="d-none d-sm-block">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>

                <!-- Page Title for mobile -->
                <div class="d-sm-none">
                    <h6 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h6>
                </div>
            </div>

            <!-- Right side with search and user menu -->
            <div class="col-md-6">
                <div class="d-flex justify-content-end align-items-center flex-wrap gap-2">
                    <!-- Search Bar - Hidden on mobile, shown on tablet+ -->
                    <div class="me-2 d-none d-md-block" style="width: 300px;">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Rechercher..." id="globalSearch">
                            <button class="btn btn-outline-secondary btn-sm" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Search Toggle -->
                    <button class="btn btn-outline-secondary btn-sm me-2 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="dropdown me-2">
                        <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 320px;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <small class="text-muted">3 nouvelles</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start py-2" href="#">
                                    <div class="bg-primary rounded-circle me-2 mt-1" style="width: 8px; height: 8px;"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">Nouveau pèlerin inscrit</div>
                                        <div class="text-muted small">Ahmed Ben Ali s'est inscrit</div>
                                        <div class="text-muted small">Il y a 5 minutes</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start py-2" href="#">
                                    <div class="bg-success rounded-circle me-2 mt-1" style="width: 8px; height: 8px;"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">Paiement reçu</div>
                                        <div class="text-muted small">Paiement de 15,000 FCFA reçu</div>
                                        <div class="text-muted small">Il y a 1 heure</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start py-2" href="#">
                                    <div class="bg-warning rounded-circle me-2 mt-1" style="width: 8px; height: 8px;"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">Document manquant</div>
                                        <div class="text-muted small">Passeport requis pour Fatima Zahra</div>
                                        <div class="text-muted small">Il y a 2 heures</div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center">
                                <a class="dropdown-item text-primary small" href="#">Voir toutes les notifications</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-plus me-1"></i>
                            <span class="d-none d-sm-inline">Nouveau</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('campaigns.create') }}">
                                    <i class="fas fa-flag me-2"></i>Campagne
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('pilgrims.create') }}">
                                    <i class="fas fa-user-plus me-2"></i>Pèlerin
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('payments.create') }}">
                                    <i class="fas fa-credit-card me-2"></i>Paiement
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Documents are now managed per pilgrim -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar (Collapsible) -->
        <div class="collapse d-md-none" id="mobileSearch">
            <div class="border-top p-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Rechercher..." id="mobileGlobalSearch">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>