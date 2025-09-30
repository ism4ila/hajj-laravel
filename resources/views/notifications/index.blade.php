@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bell me-2"></i>Notifications</h2>
        <div>
            <button class="btn btn-outline-primary btn-sm me-2" id="markAllRead">
                <i class="fas fa-check-double"></i> Tout marquer comme lu
            </button>
            <button class="btn btn-outline-danger btn-sm" id="clearRead">
                <i class="fas fa-trash"></i> Supprimer les lues
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total</h5>
                            <h3>{{ $notifications->count() }}</h3>
                        </div>
                        <i class="fas fa-bell fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Non lues</h5>
                            <h3>{{ $notifications->where('read', false)->count() }}</h3>
                        </div>
                        <i class="fas fa-envelope fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Aujourd'hui</h5>
                            <h3>{{ $notifications->where('created_at', '>=', today())->count() }}</h3>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Importantes</h5>
                            <h3>{{ $notifications->whereIn('type', ['danger', 'warning'])->count() }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Toutes les notifications</h5>
        </div>
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <div class="notification-item border-bottom {{ $notification['read'] ? '' : 'bg-light' }}" data-id="{{ $notification['id'] }}">
                    <div class="d-flex align-items-start p-3">
                        <!-- Notification Icon -->
                        <div class="notification-icon me-3 mt-1">
                            @php
                                $iconColor = match($notification['type']) {
                                    'success' => 'text-success',
                                    'warning' => 'text-warning',
                                    'danger' => 'text-danger',
                                    'info' => 'text-info',
                                    default => 'text-secondary'
                                };
                            @endphp
                            <i class="{{ $notification['icon'] }} {{ $iconColor }} fa-lg"></i>
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-1 {{ $notification['read'] ? 'text-muted' : 'fw-bold' }}">
                                    {{ $notification['title'] }}
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $notification['created_at']->diffForHumans() }}</small>
                                    @if(!$notification['read'])
                                        <span class="badge bg-primary">Nouveau</span>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-2 {{ $notification['read'] ? 'text-muted' : '' }}">
                                {{ $notification['message'] }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ $notification['url'] }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Voir détails
                                </a>
                                <div class="btn-group btn-group-sm">
                                    @if(!$notification['read'])
                                        <button class="btn btn-outline-success mark-read" data-id="{{ $notification['id'] }}" title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger delete-notification" data-id="{{ $notification['id'] }}" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune notification</h5>
                    <p class="text-muted">Vous êtes à jour ! Aucune notification à afficher.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark single notification as read
    document.querySelectorAll('.mark-read').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId, this);
        });
    });

    // Mark all as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        if (confirm('Marquer toutes les notifications comme lues ?')) {
            // Simulate marking all as read
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('bg-light');
                const title = item.querySelector('h6');
                if (title) title.classList.remove('fw-bold');
                const badge = item.querySelector('.badge');
                if (badge) badge.remove();
                const markBtn = item.querySelector('.mark-read');
                if (markBtn) markBtn.remove();
            });

            // Update statistics
            const unreadCount = document.querySelector('.bg-success h3');
            if (unreadCount) unreadCount.textContent = '0';

            alert('Toutes les notifications ont été marquées comme lues.');
        }
    });

    // Clear read notifications
    document.getElementById('clearRead').addEventListener('click', function() {
        if (confirm('Supprimer toutes les notifications lues ?')) {
            // Remove read notifications
            document.querySelectorAll('.notification-item:not(.bg-light)').forEach(item => {
                item.remove();
            });

            // Update total count
            const totalCount = document.querySelector('.bg-primary h3');
            const unreadCount = parseInt(document.querySelector('.bg-success h3').textContent);
            if (totalCount) totalCount.textContent = unreadCount;

            alert('Les notifications lues ont été supprimées.');
        }
    });

    // Delete individual notification
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Supprimer cette notification ?')) {
                const notificationItem = this.closest('.notification-item');
                notificationItem.remove();

                // Update counts
                updateCounts();

                alert('Notification supprimée.');
            }
        });
    });

    function markAsRead(notificationId, button) {
        // Simulate API call
        setTimeout(() => {
            const notificationItem = button.closest('.notification-item');
            notificationItem.classList.remove('bg-light');

            const title = notificationItem.querySelector('h6');
            if (title) title.classList.remove('fw-bold');

            const badge = notificationItem.querySelector('.badge');
            if (badge) badge.remove();

            button.remove();

            // Update unread count
            const unreadCount = document.querySelector('.bg-success h3');
            if (unreadCount) {
                const current = parseInt(unreadCount.textContent);
                unreadCount.textContent = Math.max(0, current - 1);
            }
        }, 500);
    }

    function updateCounts() {
        const totalItems = document.querySelectorAll('.notification-item').length;
        const unreadItems = document.querySelectorAll('.notification-item.bg-light').length;

        const totalCount = document.querySelector('.bg-primary h3');
        const unreadCount = document.querySelector('.bg-success h3');

        if (totalCount) totalCount.textContent = totalItems;
        if (unreadCount) unreadCount.textContent = unreadItems;
    }
});
</script>
@endpush