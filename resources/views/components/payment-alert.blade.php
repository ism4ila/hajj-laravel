@props([
    'pilgrim' => null,
    'type' => 'warning', // warning, danger, info
    'dismissible' => false
])

@php
    $alertClasses = [
        'warning' => 'alert-warning',
        'danger' => 'alert-danger',
        'info' => 'alert-info',
        'success' => 'alert-success'
    ];

    $icons = [
        'warning' => 'fas fa-exclamation-triangle',
        'danger' => 'fas fa-times-circle',
        'info' => 'fas fa-info-circle',
        'success' => 'fas fa-check-circle'
    ];

    $alertClass = $alertClasses[$type] ?? 'alert-warning';
    $icon = $icons[$type] ?? 'fas fa-exclamation-triangle';
@endphp

<div class="alert {{ $alertClass }} {{ $dismissible ? 'alert-dismissible' : '' }} fade show" role="alert">
    <div class="d-flex align-items-start">
        <i class="{{ $icon }} me-3 mt-1"></i>
        <div class="flex-grow-1">
            @if($pilgrim)
                <div class="fw-bold mb-1">
                    {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}
                </div>

                @if($pilgrim->remaining_amount > 0)
                    <div class="mb-2">
                        Montant restant à payer : <strong>{{ number_format($pilgrim->remaining_amount, 0, ',', ' ') }} DH</strong>
                    </div>

                    @php
                        $percentage = $pilgrim->total_amount > 0 ? ($pilgrim->paid_amount / $pilgrim->total_amount) * 100 : 0;
                        $urgency = '';
                        if ($percentage < 25) {
                            $urgency = 'Paiement très en retard';
                        } elseif ($percentage < 50) {
                            $urgency = 'Paiement en retard';
                        } elseif ($percentage < 75) {
                            $urgency = 'Paiement à compléter';
                        } else {
                            $urgency = 'Derniers paiements';
                        }
                    @endphp

                    <div class="small text-muted mb-2">
                        {{ $urgency }} - {{ number_format($percentage, 1) }}% payé
                    </div>

                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                             style="width: {{ $percentage }}%"></div>
                    </div>

                    @can('manage-payments')
                    <div class="d-flex gap-2">
                        <a href="{{ route('payments.create', ['pilgrim' => $pilgrim->id]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Ajouter paiement
                        </a>
                        <a href="{{ route('pilgrims.show', $pilgrim) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i>Voir détails
                        </a>
                    </div>
                    @endcan
                @else
                    <div class="text-success">
                        <i class="fas fa-check-circle me-1"></i>Paiement complet
                    </div>
                @endif
            @else
                {{ $slot }}
            @endif
        </div>

        @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        @endif
    </div>
</div>