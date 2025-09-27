@foreach($clients as $client)
<tr class="client-row" data-client-id="{{ $client->id }}">
    <!-- Client Info avec sélection -->
    <td class="align-middle">
        <div class="d-flex align-items-center">
            <div class="form-check me-3">
                <input class="form-check-input client-checkbox" type="checkbox" value="{{ $client->id }}">
            </div>
            <div class="position-relative me-3">
                <div class="avatar bg-gradient-primary text-white rounded-circle shadow-sm"
                     style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}
                </div>
                @if($client->is_active)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 0.5rem;">
                        <i class="fas fa-check"></i>
                    </span>
                @endif
            </div>
            <div>
                <div class="fw-semibold text-dark mb-1">
                    <a href="{{ route('clients.show', $client) }}" class="text-decoration-none text-dark hover-primary">
                        {{ $client->full_name }}
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2 small text-muted">
                    <span class="badge bg-light text-dark">{{ $client->age }} ans</span>
                    <span class="text-{{ $client->gender === 'male' ? 'primary' : 'danger' }}">
                        {{ $client->gender === 'male' ? '♂' : '♀' }}
                    </span>
                    @if($client->nationality)
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="fas fa-flag me-1"></i>{{ $client->nationality }}
                        </span>
                        @if($client->nationality === 'Cameroun' && $client->region)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $client->region }}
                            </span>
                        @endif
                    @endif
                </div>
                <!-- Mobile Contact Info -->
                <div class="d-md-none mt-2">
                    @if($client->phone)
                        <div class="small text-muted mb-1">
                            <i class="fas fa-phone text-success me-1"></i>
                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                        </div>
                    @endif
                    @if($client->email)
                        <div class="small text-muted">
                            <i class="fas fa-envelope text-primary me-1"></i>
                            <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </td>

    <!-- Contact (Hidden on mobile) -->
    <td class="d-none d-md-table-cell align-middle">
        @if($client->phone || $client->email)
            @if($client->phone)
                <div class="small mb-1">
                    <i class="fas fa-phone text-success me-2"></i>
                    <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                </div>
            @endif
            @if($client->email)
                <div class="small">
                    <i class="fas fa-envelope text-primary me-2"></i>
                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ Str::limit($client->email, 20) }}</a>
                </div>
            @endif
        @else
            <span class="text-muted small">
                <i class="fas fa-minus"></i> Non renseigné
            </span>
        @endif
    </td>

    <!-- Pilgrimages (Hidden on tablet) -->
    <td class="d-none d-lg-table-cell align-middle">
        <div class="text-center">
            @if($client->pilgrims->count() > 0)
                <div class="position-relative d-inline-block">
                    <div class="h4 mb-0 text-primary fw-bold">{{ $client->pilgrims->count() }}</div>
                    <small class="text-muted">pèlerinages</small>
                    @if($client->pilgrims->count() > 1)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <i class="fas fa-star" style="font-size: 0.6rem;"></i>
                        </span>
                    @endif
                </div>
            @else
                <div class="text-muted">
                    <i class="fas fa-minus"></i>
                    <br><small>Aucun</small>
                </div>
            @endif
        </div>
    </td>

    <!-- Status -->
    <td class="align-middle">
        <div class="d-flex flex-column align-items-start">
            @if($client->is_active)
                <span class="badge bg-success bg-opacity-15 text-success border border-success">
                    <i class="fas fa-check-circle me-1"></i>Actif
                </span>
            @else
                <span class="badge bg-secondary bg-opacity-15 text-secondary border border-secondary">
                    <i class="fas fa-pause-circle me-1"></i>Inactif
                </span>
            @endif

            <!-- Mobile Pilgrimages Count -->
            <div class="d-lg-none mt-2">
                <small class="text-muted">
                    <i class="fas fa-route me-1"></i>{{ $client->pilgrims->count() }} pèlerinages
                </small>
            </div>
        </div>
    </td>

    <!-- Actions -->
    <td class="align-middle">
        <div class="d-flex justify-content-center gap-1">
            <a href="{{ route('clients.show', $client) }}"
               class="btn btn-sm btn-outline-primary"
               data-bs-toggle="tooltip"
               title="Voir le profil">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('clients.edit', $client) }}"
               class="btn btn-sm btn-outline-warning"
               data-bs-toggle="tooltip"
               title="Modifier">
                <i class="fas fa-edit"></i>
            </a>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary"
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-toggle="tooltip"
                        title="Plus d'actions">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <h6 class="dropdown-header">
                            <i class="fas fa-user me-2"></i>{{ $client->full_name }}
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('clients.show', $client) }}">
                            <i class="fas fa-eye me-2 text-primary"></i>Voir le profil complet
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('clients.edit', $client) }}">
                            <i class="fas fa-edit me-2 text-warning"></i>Modifier les informations
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('pilgrims.create', ['client_id' => $client->id]) }}">
                            <i class="fas fa-plus me-2 text-success"></i>Nouveau pèlerinage
                        </a>
                    </li>
                    @if($client->pilgrims->count() === 0)
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement ce client ?')"
                                  class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </td>
</tr>
@endforeach