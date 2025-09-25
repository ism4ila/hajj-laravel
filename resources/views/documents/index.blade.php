@extends('layouts.app')

@section('title', 'Documents - ' . $pilgrim->firstname . ' ' . $pilgrim->lastname)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.index') }}">Pèlerins</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pilgrims.show', $pilgrim) }}">{{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</a></li>
    <li class="breadcrumb-item active">Documents</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="avatar bg-primary text-white rounded-circle me-3"
             style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
            {{ substr($pilgrim->firstname, 0, 1) }}{{ substr($pilgrim->lastname, 0, 1) }}
        </div>
        <div>
            <h1 class="h3 mb-0">Documents - {{ $pilgrim->firstname }} {{ $pilgrim->lastname }}</h1>
            <p class="text-muted mb-0">
                {{ $pilgrim->campaign?->name }}
                @if($document->documents_complete)
                    <x-badge variant="success" class="ms-2">
                        <i class="fas fa-check me-1"></i>Complets
                    </x-badge>
                @else
                    <x-badge variant="warning" class="ms-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>Incomplets
                    </x-badge>
                @endif
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <x-button href="{{ route('documents.checklist', $pilgrim) }}" variant="outline-info" icon="fas fa-list">
            Checklist
        </x-button>
        <x-button href="{{ route('pilgrims.show', $pilgrim) }}" variant="outline-secondary" icon="fas fa-arrow-left">
            Retour
        </x-button>
    </div>
</div>

<!-- Documents Status -->
<div class="row mb-4">
    <div class="col-md-4">
        <x-card class="text-center h-100 {{ $document->documents_complete ? 'bg-success text-white' : 'bg-warning text-dark' }}">
            <i class="fas fa-{{ $document->documents_complete ? 'check-circle' : 'exclamation-triangle' }} fa-3x mb-3"></i>
            <h5>{{ $document->documents_complete ? 'Documents Complets' : 'Documents Incomplets' }}</h5>
            <p class="mb-0">
                @if($document->documents_complete)
                    Tous les documents requis sont fournis
                @else
                    {{ count($document->missing_documents) }} document(s) manquant(s)
                @endif
            </p>
        </x-card>
    </div>
    <div class="col-md-8">
        @if(!$document->documents_complete && count($document->missing_documents) > 0)
        <x-card title="📋 Documents Manquants" class="h-100 border-warning">
            <ul class="list-unstyled mb-0">
                @foreach($document->missing_documents as $missing)
                    <li class="mb-1">
                        <i class="fas fa-times text-danger me-2"></i>{{ $missing }}
                    </li>
                @endforeach
            </ul>
        </x-card>
        @else
        <x-card title="✅ Statut" class="h-100 border-success">
            <div class="text-success">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <p class="mb-0">Tous les documents requis ont été fournis et vérifiés.</p>
            </div>
        </x-card>
        @endif
    </div>
</div>

<!-- Documents Form -->
<form method="POST" action="{{ route('documents.update', $pilgrim) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- CNI -->
        <div class="col-lg-6">
            <x-card title="🆔 Carte d'Identité Nationale" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="cni"
                            label="Numéro CNI"
                            placeholder="A123456789"
                            :value="old('cni', $document->cni)"
                        />
                    </div>
                    <div class="col-md-6">
                        <label for="cni_file" class="form-label">
                            Fichier CNI
                            @if($document->cni_file)
                                <x-badge variant="success" class="ms-1">✓</x-badge>
                            @endif
                        </label>
                        <input type="file"
                               class="form-control @error('cni_file') is-invalid @enderror"
                               id="cni_file"
                               name="cni_file"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('cni_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">PDF, JPG, PNG (max 2MB)</div>
                    </div>
                </div>

                @if($document->cni_file)
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file text-primary me-2"></i>
                            <span>Fichier CNI téléchargé</span>
                        </div>
                        <div>
                            <a href="{{ route('documents.download', [$pilgrim, 'cni']) }}"
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.delete-file', [$pilgrim, 'cni']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce fichier ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </x-card>
        </div>

        <!-- Passport -->
        <div class="col-lg-6">
            <x-card title="📖 Passeport" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="passport"
                            label="Numéro Passeport"
                            placeholder="AB1234567"
                            :value="old('passport', $document->passport)"
                        />
                    </div>
                    <div class="col-md-6">
                        <label for="passport_file" class="form-label">
                            Fichier Passeport
                            @if($document->passport_file)
                                <x-badge variant="success" class="ms-1">✓</x-badge>
                            @endif
                        </label>
                        <input type="file"
                               class="form-control @error('passport_file') is-invalid @enderror"
                               id="passport_file"
                               name="passport_file"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('passport_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">PDF, JPG, PNG (max 2MB)</div>
                    </div>
                </div>

                @if($document->passport_file)
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file text-primary me-2"></i>
                            <span>Fichier passeport téléchargé</span>
                        </div>
                        <div>
                            <a href="{{ route('documents.download', [$pilgrim, 'passport']) }}"
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.delete-file', [$pilgrim, 'passport']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce fichier ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </x-card>
        </div>

        <!-- Visa -->
        <div class="col-lg-6">
            <x-card title="📋 Visa" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="visa"
                            label="Numéro Visa"
                            placeholder="VS123456789"
                            :value="old('visa', $document->visa)"
                        />
                    </div>
                    <div class="col-md-6">
                        <label for="visa_file" class="form-label">
                            Fichier Visa
                            @if($document->visa_file)
                                <x-badge variant="success" class="ms-1">✓</x-badge>
                            @endif
                            @if($pilgrim->campaign?->type === 'hajj')
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input type="file"
                               class="form-control @error('visa_file') is-invalid @enderror"
                               id="visa_file"
                               name="visa_file"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('visa_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            PDF, JPG, PNG (max 2MB)
                            @if($pilgrim->campaign?->type === 'hajj')
                                <br><small class="text-danger">Requis pour le Hajj</small>
                            @endif
                        </div>
                    </div>
                </div>

                @if($document->visa_file)
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file text-primary me-2"></i>
                            <span>Fichier visa téléchargé</span>
                        </div>
                        <div>
                            <a href="{{ route('documents.download', [$pilgrim, 'visa']) }}"
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.delete-file', [$pilgrim, 'visa']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce fichier ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </x-card>
        </div>

        <!-- Vaccination Certificate -->
        <div class="col-lg-6">
            <x-card title="💉 Certificat de Vaccination" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="vaccination_certificate"
                            label="Numéro Certificat"
                            placeholder="VC123456789"
                            :value="old('vaccination_certificate', $document->vaccination_certificate)"
                        />
                    </div>
                    <div class="col-md-6">
                        <label for="vaccination_file" class="form-label">
                            Fichier Certificat
                            @if($document->vaccination_file)
                                <x-badge variant="success" class="ms-1">✓</x-badge>
                            @endif
                        </label>
                        <input type="file"
                               class="form-control @error('vaccination_file') is-invalid @enderror"
                               id="vaccination_file"
                               name="vaccination_file"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('vaccination_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">PDF, JPG, PNG (max 2MB)</div>
                    </div>
                </div>

                @if($document->vaccination_file)
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file text-primary me-2"></i>
                            <span>Certificat de vaccination téléchargé</span>
                        </div>
                        <div>
                            <a href="{{ route('documents.download', [$pilgrim, 'vaccination']) }}"
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.delete-file', [$pilgrim, 'vaccination']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce fichier ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </x-card>
        </div>

        <!-- Photo -->
        <div class="col-lg-12">
            <x-card title="📷 Photo d'Identité" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="photo_file" class="form-label">
                            Photo d'identité
                            @if($document->photo_file)
                                <x-badge variant="success" class="ms-1">✓</x-badge>
                            @endif
                            <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               class="form-control @error('photo_file') is-invalid @enderror"
                               id="photo_file"
                               name="photo_file"
                               accept=".jpg,.jpeg,.png">
                        @error('photo_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">JPG, PNG uniquement (max 2MB) - Format passeport requis</div>
                    </div>
                    <div class="col-md-6">
                        @if($document->photo_file)
                        <div class="text-center">
                            <img src="{{ Storage::url($document->photo_file) }}"
                                 alt="Photo identité"
                                 class="img-thumbnail"
                                 style="max-width: 150px; max-height: 200px;">
                        </div>
                        @endif
                    </div>
                </div>

                @if($document->photo_file)
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-image text-primary me-2"></i>
                            <span>Photo d'identité téléchargée</span>
                        </div>
                        <div>
                            <a href="{{ route('documents.download', [$pilgrim, 'photo']) }}"
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.delete-file', [$pilgrim, 'photo']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce fichier ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="text-muted">
                <span class="text-danger">*</span> Champs requis
                @if($pilgrim->campaign?->type === 'hajj')
                    • Visa requis pour le Hajj
                @endif
            </small>
        </div>
        <div class="d-flex gap-2">
            @can('manage-documents')
            <x-button type="submit" variant="primary" icon="fas fa-save">
                Sauvegarder les Documents
            </x-button>
            @endcan
            <x-button href="{{ route('pilgrims.show', $pilgrim) }}" variant="outline-secondary">
                Annuler
            </x-button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview photo upload
    const photoInput = document.getElementById('photo_file');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update existing preview or create new one
                    const existingPreview = document.querySelector('.img-thumbnail');
                    if (existingPreview) {
                        existingPreview.src = e.target.result;
                    } else {
                        const preview = document.createElement('img');
                        preview.className = 'img-thumbnail mt-2';
                        preview.style.maxWidth = '150px';
                        preview.style.maxHeight = '200px';
                        preview.src = e.target.result;
                        photoInput.parentElement.appendChild(preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) { // 2MB
                alert('Le fichier est trop volumineux. Taille maximum: 2MB');
                this.value = '';
            }
        });
    });
});
</script>
@endpush