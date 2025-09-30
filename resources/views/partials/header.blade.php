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
                    <div class="me-2 d-none d-md-block position-relative" style="width: 300px;">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Rechercher..." id="globalSearch" autocomplete="off">
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <!-- Search Results Dropdown -->
                        <div class="position-absolute w-100 bg-white border rounded-3 shadow-lg mt-1 d-none" id="searchResults" style="z-index: 1050; max-height: 400px; overflow-y: auto;">
                            <div class="p-2">
                                <div class="text-center py-3">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <small class="text-muted d-block">Recherche en cours...</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Search Toggle -->
                    <button class="btn btn-outline-secondary btn-sm me-2 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="dropdown me-2">
                        <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown" id="notificationsDropdown">
                            <i class="fas fa-bell" id="notificationIcon"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;" id="notificationCount">
                                0
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 320px;" id="notificationsDropdownMenu">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <small class="text-muted" id="notificationsSummary">Chargement...</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li id="notificationsContainer">
                                <div class="text-center py-3">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <small class="text-muted d-block mt-1">Chargement des notifications...</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center">
                                <a class="dropdown-item text-primary small" href="{{ route('notifications.index') }}">Voir toutes les notifications</a>
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
                                <a class="dropdown-item" href="{{ route('clients.create') }}">
                                    <i class="fas fa-user me-2"></i>Client
                                </a>
                            </li>
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
            <div class="border-top p-3 position-relative">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Rechercher..." id="mobileGlobalSearch" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="mobileSearchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <!-- Mobile Search Results -->
                <div class="position-absolute w-100 bg-white border rounded-3 shadow-lg mt-1 d-none" id="mobileSearchResults" style="z-index: 1050; max-height: 300px; overflow-y: auto; left: 15px; right: 15px;">
                    <div class="p-2">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin"></i>
                            <small class="text-muted d-block">Recherche en cours...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global Search functionality
    const globalSearch = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    const mobileGlobalSearch = document.getElementById('mobileGlobalSearch');
    const mobileSearchResults = document.getElementById('mobileSearchResults');

    let searchTimeout = null;

    // Function to perform search
    function performSearch(query, resultsContainer) {
        if (query.length < 2) {
            resultsContainer.classList.add('d-none');
            return;
        }

        // Show loading
        resultsContainer.innerHTML = `
            <div class="p-3">
                <div class="text-center py-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <small class="text-muted d-block mt-1">Recherche en cours...</small>
                </div>
            </div>
        `;
        resultsContainer.classList.remove('d-none');

        // Make API call
        fetch(`{{ route('global.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data, resultsContainer);
            })
            .catch(error => {
                console.error('Search error:', error);
                resultsContainer.innerHTML = `
                    <div class="p-3">
                        <div class="text-center py-2 text-muted">
                            <i class="fas fa-exclamation-triangle"></i>
                            <small class="d-block mt-1">Erreur lors de la recherche</small>
                        </div>
                    </div>
                `;
            });
    }

    // Function to display results
    function displayResults(data, resultsContainer) {
        let html = '';

        if (data.total === 0) {
            html = `
                <div class="p-3">
                    <div class="text-center py-2 text-muted">
                        <i class="fas fa-search"></i>
                        <small class="d-block mt-1">Aucun résultat trouvé</small>
                    </div>
                </div>
            `;
        } else {
            // Clients
            if (data.clients && data.clients.length > 0) {
                html += '<div class="border-bottom px-3 py-1"><small class="text-muted fw-bold">CLIENTS</small></div>';
                data.clients.forEach(item => {
                    html += `
                        <a href="${item.url}" class="dropdown-item py-2 border-0">
                            <div class="d-flex align-items-center">
                                <i class="${item.icon} text-primary me-2"></i>
                                <div>
                                    <div class="fw-semibold small">${item.title}</div>
                                    <div class="text-muted small">${item.subtitle}</div>
                                </div>
                            </div>
                        </a>
                    `;
                });
            }

            // Pilgrims
            if (data.pilgrims && data.pilgrims.length > 0) {
                html += '<div class="border-bottom px-3 py-1"><small class="text-muted fw-bold">PÈLERINS</small></div>';
                data.pilgrims.forEach(item => {
                    html += `
                        <a href="${item.url}" class="dropdown-item py-2 border-0">
                            <div class="d-flex align-items-center">
                                <i class="${item.icon} text-success me-2"></i>
                                <div>
                                    <div class="fw-semibold small">${item.title}</div>
                                    <div class="text-muted small">${item.subtitle}</div>
                                </div>
                            </div>
                        </a>
                    `;
                });
            }

            // Campaigns
            if (data.campaigns && data.campaigns.length > 0) {
                html += '<div class="border-bottom px-3 py-1"><small class="text-muted fw-bold">CAMPAGNES</small></div>';
                data.campaigns.forEach(item => {
                    html += `
                        <a href="${item.url}" class="dropdown-item py-2 border-0">
                            <div class="d-flex align-items-center">
                                <i class="${item.icon} text-warning me-2"></i>
                                <div>
                                    <div class="fw-semibold small">${item.title}</div>
                                    <div class="text-muted small">${item.subtitle}</div>
                                </div>
                            </div>
                        </a>
                    `;
                });
            }

            // Payments
            if (data.payments && data.payments.length > 0) {
                html += '<div class="border-bottom px-3 py-1"><small class="text-muted fw-bold">PAIEMENTS</small></div>';
                data.payments.forEach(item => {
                    html += `
                        <a href="${item.url}" class="dropdown-item py-2 border-0">
                            <div class="d-flex align-items-center">
                                <i class="${item.icon} text-info me-2"></i>
                                <div>
                                    <div class="fw-semibold small">${item.title}</div>
                                    <div class="text-muted small">${item.subtitle}</div>
                                </div>
                            </div>
                        </a>
                    `;
                });
            }
        }

        resultsContainer.innerHTML = html;
    }

    // Desktop search
    if (globalSearch) {
        globalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            searchTimeout = setTimeout(() => {
                performSearch(query, searchResults);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!globalSearch.closest('.position-relative').contains(e.target)) {
                searchResults.classList.add('d-none');
            }
        });
    }

    // Mobile search
    if (mobileGlobalSearch) {
        mobileGlobalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            searchTimeout = setTimeout(() => {
                performSearch(query, mobileSearchResults);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileGlobalSearch.closest('.position-relative').contains(e.target)) {
                mobileSearchResults.classList.add('d-none');
            }
        });
    }

    // Close search results when clicking on a result
    document.addEventListener('click', function(e) {
        if (e.target.closest('.dropdown-item')) {
            searchResults.classList.add('d-none');
            mobileSearchResults.classList.add('d-none');
        }
    });

    // Real-time notifications system
    let notificationsPolling;
    let lastNotificationTime = null;

    // Load initial notifications
    loadNotifications();

    // Start polling for new notifications every 30 seconds
    notificationsPolling = setInterval(loadNotifications, 30000);

    function loadNotifications() {
        fetch('{{ route("notifications.api") }}')
            .then(response => response.json())
            .then(data => {
                updateNotificationsUI(data);

                // Check for new notifications
                if (lastNotificationTime && data.notifications.length > 0) {
                    const newestNotification = new Date(data.notifications[0].created_at);
                    if (newestNotification > new Date(lastNotificationTime)) {
                        showNotificationAlert(data.notifications[0]);
                        playNotificationSound();
                    }
                }

                // Update last notification time
                if (data.notifications.length > 0) {
                    lastNotificationTime = data.notifications[0].created_at;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }

    function updateNotificationsUI(data) {
        const countBadge = document.getElementById('notificationCount');
        const summaryText = document.getElementById('notificationsSummary');
        const container = document.getElementById('notificationsContainer');
        const icon = document.getElementById('notificationIcon');

        // Update count
        if (countBadge) {
            countBadge.textContent = data.unread;
            countBadge.style.display = data.unread > 0 ? 'inline' : 'none';
        }

        // Update summary
        if (summaryText) {
            summaryText.textContent = data.unread > 0 ? `${data.unread} nouvelles` : 'Toutes lues';
        }

        // Animate bell icon if there are unread notifications
        if (icon && data.unread > 0) {
            icon.classList.add('fa-shake');
            setTimeout(() => icon.classList.remove('fa-shake'), 1000);
        }

        // Update notifications list
        if (container) {
            if (data.notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Aucune notification</p>
                    </div>
                `;
            } else {
                let html = '';
                data.notifications.forEach(notification => {
                    const timeAgo = formatTimeAgo(new Date(notification.created_at));
                    const colorClass = getNotificationColor(notification.type);

                    html += `
                        <a class="dropdown-item d-flex align-items-start py-2 notification-item ${!notification.read ? 'unread' : ''}"
                           href="${notification.url}" data-id="${notification.id}">
                            <div class="bg-${colorClass} rounded-circle me-2 mt-1" style="width: 8px; height: 8px;"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">${notification.title}</div>
                                <div class="text-muted small">${notification.message}</div>
                                <div class="text-muted small">${timeAgo}</div>
                            </div>
                            ${!notification.read ? '<div class="text-primary small">●</div>' : ''}
                        </a>
                    `;
                });
                container.innerHTML = html;
            }
        }
    }

    function getNotificationColor(type) {
        switch(type) {
            case 'success': return 'success';
            case 'warning': return 'warning';
            case 'danger': return 'danger';
            case 'info': return 'info';
            default: return 'primary';
        }
    }

    function formatTimeAgo(date) {
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));

        if (diffInMinutes < 1) return 'À l\'instant';
        if (diffInMinutes < 60) return `Il y a ${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''}`;

        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `Il y a ${diffInHours} heure${diffInHours > 1 ? 's' : ''}`;

        const diffInDays = Math.floor(diffInHours / 24);
        return `Il y a ${diffInDays} jour${diffInDays > 1 ? 's' : ''}`;
    }

    function showNotificationAlert(notification) {
        // Show browser notification if permission granted
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(notification.title, {
                body: notification.message,
                icon: '/favicon.ico',
                tag: 'hajj-notification-' + notification.id
            });
        }

        // Show in-app notification toast
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-bg-primary border-0 position-fixed';
        toast.style.cssText = 'top: 80px; right: 20px; z-index: 1080;';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${notification.icon} me-2"></i>
                    <strong>${notification.title}</strong><br>
                    <small>${notification.message}</small>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        document.body.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast, { delay: 5000 });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    function playNotificationSound() {
        // Play a subtle notification sound
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvWkfCy2Ix/PfmEQTGW+/7aGeKAYjdcLu0n4vBiV+2u7YfCkFLYHO8+OeOwslbczx1H0vBSB6yu3WeSMFl+3/8wH//wWcCiQc');
            audio.volume = 0.3;
            audio.play();
        } catch (e) {
            // Ignore audio errors
        }
    }

    // Request notification permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Cleanup polling on page unload
    window.addEventListener('beforeunload', () => {
        if (notificationsPolling) {
            clearInterval(notificationsPolling);
        }
    });

    // Add CSS for notification animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fa-shake {
            0% { transform: rotate(-15deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(-15deg); }
            75% { transform: rotate(15deg); }
            100% { transform: rotate(0deg); }
        }
        .fa-shake {
            animation: fa-shake 0.5s ease-in-out;
        }
        .notification-item.unread {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .notification-item:hover {
            background-color: rgba(0,0,0,0.05);
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush