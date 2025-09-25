<!-- Header -->
<header class="bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end align-items-center">
                    <!-- Search Bar -->
                    <div class="me-3" style="width: 300px;">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Rechercher..." id="globalSearch">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="dropdown me-3">
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
                                        <div class="text-muted small">Paiement de 15,000 DH reçu</div>
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
                        <button class="btn btn-primary" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-plus me-1"></i>
                            Nouveau
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
    </div>
</header>