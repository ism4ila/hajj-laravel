<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display documents for a specific pilgrim.
     */
    public function index(Pilgrim $pilgrim): View
    {
        $pilgrim->load(['campaign']);

        // Get or create document record
        $document = Document::firstOrCreate(
            ['pilgrim_id' => $pilgrim->id],
            [
                'cni' => null,
                'cni_file' => null,
                'passport' => null,
                'passport_file' => null,
                'visa' => null,
                'visa_file' => null,
                'vaccination_certificate' => null,
                'vaccination_file' => null,
                'photo_file' => null,
                'documents_complete' => false,
            ]
        );

        return view('documents.index', compact('pilgrim', 'document'));
    }

    /**
     * Update documents for a specific pilgrim.
     */
    public function update(Request $request, Pilgrim $pilgrim): RedirectResponse
    {
        Gate::authorize('manage-documents');

        $validated = $request->validate([
            'cni' => 'nullable|string|max:255',
            'cni_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passport' => 'nullable|string|max:255',
            'passport_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'visa' => 'nullable|string|max:255',
            'visa_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'vaccination_certificate' => 'nullable|string|max:255',
            'vaccination_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'cni_file.mimes' => 'Le fichier CNI doit être au format PDF, JPG, JPEG ou PNG.',
            'cni_file.max' => 'Le fichier CNI ne peut pas dépasser 2 MB.',
            'passport_file.mimes' => 'Le fichier passeport doit être au format PDF, JPG, JPEG ou PNG.',
            'passport_file.max' => 'Le fichier passeport ne peut pas dépasser 2 MB.',
            'visa_file.mimes' => 'Le fichier visa doit être au format PDF, JPG, JPEG ou PNG.',
            'visa_file.max' => 'Le fichier visa ne peut pas dépasser 2 MB.',
            'vaccination_file.mimes' => 'Le fichier certificat de vaccination doit être au format PDF, JPG, JPEG ou PNG.',
            'vaccination_file.max' => 'Le fichier certificat de vaccination ne peut pas dépasser 2 MB.',
            'photo_file.mimes' => 'La photo doit être au format JPG, JPEG ou PNG.',
            'photo_file.max' => 'La photo ne peut pas dépasser 2 MB.',
        ]);

        // Get or create document record
        $document = Document::firstOrCreate(['pilgrim_id' => $pilgrim->id]);

        // Handle file uploads
        $fileFields = ['cni_file', 'passport_file', 'visa_file', 'vaccination_file', 'photo_file'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($document->$field && Storage::exists($document->$field)) {
                    Storage::delete($document->$field);
                }

                // Store new file
                $path = $request->file($field)->store('documents/' . $pilgrim->id, 'public');
                $validated[$field] = $path;
            }
        }

        // Update document record
        $document->update($validated);

        // Check document completeness
        $document->checkCompleteness();

        return redirect()->route('documents.index', $pilgrim)
            ->with('success', 'Documents mis à jour avec succès.');
    }

    /**
     * Download a specific document file.
     */
    public function download(Pilgrim $pilgrim, string $type): mixed
    {
        Gate::authorize('view-documents');

        $document = Document::where('pilgrim_id', $pilgrim->id)->first();

        if (!$document) {
            abort(404, 'Document non trouvé.');
        }

        $fileField = $type . '_file';

        if (!$document->$fileField || !Storage::exists($document->$fileField)) {
            abort(404, 'Fichier non trouvé.');
        }

        $fileName = $pilgrim->firstname . '_' . $pilgrim->lastname . '_' . $type . '.' .
                   pathinfo($document->$fileField, PATHINFO_EXTENSION);

        return Storage::download($document->$fileField, $fileName);
    }

    /**
     * Delete a specific document file.
     */
    public function deleteFile(Request $request, Pilgrim $pilgrim, string $type): RedirectResponse
    {
        Gate::authorize('manage-documents');

        $document = Document::where('pilgrim_id', $pilgrim->id)->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        $fileField = $type . '_file';
        $textField = $type;

        // Delete file from storage
        if ($document->$fileField && Storage::exists($document->$fileField)) {
            Storage::delete($document->$fileField);
        }

        // Clear database fields
        $document->update([
            $fileField => null,
            $textField => null,
        ]);

        // Recheck completeness
        $document->checkCompleteness();

        return redirect()->back()
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Generate documents checklist for a pilgrim.
     */
    public function checklist(Pilgrim $pilgrim): View
    {
        $pilgrim->load(['campaign']);
        $document = Document::where('pilgrim_id', $pilgrim->id)->first();

        $documents = [
            'cni' => [
                'name' => 'Carte d\'identité nationale',
                'required' => true,
                'description' => 'Copie recto-verso de la CNI en cours de validité',
                'icon' => 'fas fa-id-card',
            ],
            'passport' => [
                'name' => 'Passeport',
                'required' => true,
                'description' => 'Passeport valide au moins 6 mois après la date de retour',
                'icon' => 'fas fa-passport',
            ],
            'visa' => [
                'name' => 'Visa',
                'required' => $pilgrim->campaign?->type === 'hajj',
                'description' => 'Visa pour l\'Arabie Saoudite',
                'icon' => 'fas fa-stamp',
            ],
            'vaccination_certificate' => [
                'name' => 'Certificat de vaccination',
                'required' => true,
                'description' => 'Certificat de vaccination international',
                'icon' => 'fas fa-syringe',
            ],
            'photo_file' => [
                'name' => 'Photo d\'identité',
                'required' => true,
                'description' => 'Photo récente au format passeport',
                'icon' => 'fas fa-camera',
            ],
        ];

        return view('documents.checklist', compact('pilgrim', 'document', 'documents'));
    }
}