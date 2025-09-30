@extends('layouts.app')

@section('title', 'Gestion des Clients')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>Gestion des Clients</h2>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Client
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Clients</h5>
                            <h3>{{ $clients->total() }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Clients Actifs</h5>
                            <h3>{{ $clients->where('is_active', true)->count() }}</h3>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Hommes</h5>
                            <h3>{{ $clients->where('gender', 'male')->count() }}</h3>
                        </div>
                        <i class="fas fa-male fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Femmes</h5>
                            <h3>{{ $clients->where('gender', 'female')->count() }}</h3>
                        </div>
                        <i class="fas fa-female fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Rechercher et Filtrer</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom, téléphone...">
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Genre</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Tous</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="region" class="form-label">Région</label>
                        <select class="form-select" id="region" name="region">
                            <option value="">Toutes</option>
                            @foreach(['Douala', 'Yaoundé', 'Garoua', 'Maroua', 'Bamenda', 'Bafoussam', 'Ngaoundéré', 'Bertoua', 'Edéa', 'Kribi'] as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions en lot -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="bulkActionForm" method="POST" action="{{ route('clients.bulk-action') }}">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="bulkAction" class="form-label">Actions en lot</label>
                        <select class="form-select" id="bulkAction" name="action">
                            <option value="">Choisir une action...</option>
                            <option value="activate">Activer</option>
                            <option value="deactivate">Désactiver</option>
                            <option value="delete">Supprimer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-warning" id="bulkActionBtn" disabled>
                            <i class="fas fa-tasks"></i> Exécuter
                        </button>
                    </div>
                    <div class="col-md-3 ms-auto">
                        <a href="{{ route('exports.clients') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Exporter Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des clients -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="clientsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Nom Complet</th>
                            <th>Genre</th>
                            <th>Âge</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Pèlerinages</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_clients[]"
                                           value="{{ $client->id }}"
                                           class="form-check-input client-checkbox">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>{{ $client->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">Créé le {{ $client->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-{{ $client->gender == 'male' ? 'male' : 'female' }} me-1"></i>
                                    {{ $client->gender == 'male' ? 'Homme' : 'Femme' }}
                                </td>
                                <td>{{ $client->age }} ans</td>
                                <td>{{ $client->phone }}</td>
                                <td>
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $client->region ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('clients.toggle-status', $client) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $client->is_active ? 'success' : 'danger' }}"
                                                title="Cliquer pour {{ $client->is_active ? 'désactiver' : 'activer' }}">
                                            <i class="fas fa-{{ $client->is_active ? 'check' : 'times' }}"></i>
                                            {{ $client->is_active ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $client->pilgrims_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('clients.show', $client) }}"
                                           class="btn btn-outline-info"
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client) }}"
                                           class="btn btn-outline-warning"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('clients.destroy', $client) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>Aucun client trouvé</h5>
                                        <p>Aucun client ne correspond à vos critères de recherche.</p>
                                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Créer le premier client
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des cases à cocher
    const selectAllCheckbox = document.getElementById('selectAll');
    const clientCheckboxes = document.querySelectorAll('.client-checkbox');
    const bulkActionSelect = document.getElementById('bulkAction');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    const bulkActionForm = document.getElementById('bulkActionForm');

    // Sélectionner/Désélectionner tout
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            clientCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }

    // Gérer les cases individuelles
    clientCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(clientCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(clientCheckboxes).some(cb => cb.checked);

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }

            updateBulkActionButton();
        });
    });

    // Activer/Désactiver le bouton d'action en lot
    function updateBulkActionButton() {
        const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
        const hasSelection = checkedBoxes.length > 0;
        const hasAction = bulkActionSelect && bulkActionSelect.value !== '';

        if (bulkActionBtn) {
            bulkActionBtn.disabled = !(hasSelection && hasAction);
        }
    }

    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', updateBulkActionButton);
    }

    // Confirmation pour les actions en lot
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            const action = bulkActionSelect ? bulkActionSelect.value : '';

            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un client.');
                return;
            }

            let message = '';
            switch(action) {
                case 'activate':
                    message = `Êtes-vous sûr de vouloir activer ${checkedBoxes.length} client(s) ?`;
                    break;
                case 'deactivate':
                    message = `Êtes-vous sûr de vouloir désactiver ${checkedBoxes.length} client(s) ?`;
                    break;
                case 'delete':
                    message = `Êtes-vous sûr de vouloir supprimer définitivement ${checkedBoxes.length} client(s) ?`;
                    break;
            }

            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    }

    // Recherche en temps réel (optionnel)
    const searchInput = document.getElementById('search');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
@endpush