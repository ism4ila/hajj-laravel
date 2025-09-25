<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function show($pilgrimId): JsonResponse
    {
        $pilgrim = Pilgrim::with('documents')->find($pilgrimId);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        $documents = $pilgrim->documents;

        return response()->json([
            'success' => true,
            'data' => [
                'pilgrim' => [
                    'id' => $pilgrim->id,
                    'full_name' => $pilgrim->full_name,
                ],
                'documents' => $documents ? [
                    'id' => $documents->id,
                    'cni' => $documents->cni,
                    'cni_file' => $documents->cni_file,
                    'passport' => $documents->passport,
                    'passport_file' => $documents->passport_file,
                    'visa' => $documents->visa,
                    'visa_file' => $documents->visa_file,
                    'vaccination_certificate' => $documents->vaccination_certificate,
                    'vaccination_file' => $documents->vaccination_file,
                    'photo_file' => $documents->photo_file,
                    'documents_complete' => $documents->documents_complete,
                    'missing_documents' => $documents->missing_documents,
                    'updated_at' => $documents->updated_at->format('Y-m-d H:i:s'),
                ] : null,
                'status' => $documents?->documents_complete ? 'complete' : 'incomplete',
                'completion_rate' => $documents ? $this->getCompletionRate($documents) : 0,
            ]
        ]);
    }

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pilgrim_id' => 'required|exists:pilgrims,id',
            'document_type' => 'required|in:cni,passport,visa,vaccination,photo',
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
            'document_info' => 'nullable|string|max:255', // Pour numéro de document, etc.
        ]);

        $pilgrim = Pilgrim::find($validated['pilgrim_id']);
        $document = $pilgrim->documents()->firstOrCreate(['pilgrim_id' => $pilgrim->id]);

        // Upload du fichier
        $file = $request->file('file');
        $filename = $this->generateFilename($pilgrim, $validated['document_type'], $file->getClientOriginalExtension());
        $path = $file->storeAs('documents/' . $pilgrim->id, $filename, 'public');

        // Mise à jour des champs selon le type de document
        switch ($validated['document_type']) {
            case 'cni':
                $document->cni = $validated['document_info'] ?? 'Fourni';
                $document->cni_file = $path;
                break;
            case 'passport':
                $document->passport = $validated['document_info'] ?? 'Fourni';
                $document->passport_file = $path;
                break;
            case 'visa':
                $document->visa = $validated['document_info'] ?? 'Fourni';
                $document->visa_file = $path;
                break;
            case 'vaccination':
                $document->vaccination_certificate = $validated['document_info'] ?? 'Fourni';
                $document->vaccination_file = $path;
                break;
            case 'photo':
                $document->photo_file = $path;
                break;
        }

        $document->save();
        $document->checkCompleteness();

        // Mise à jour du statut du pèlerin si documents complets
        if ($document->documents_complete && $pilgrim->status === 'paid') {
            $pilgrim->update(['status' => 'documents_ready']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Document uploadé avec succès',
            'data' => [
                'document_type' => $validated['document_type'],
                'filename' => $filename,
                'path' => $path,
                'url' => Storage::url($path),
                'documents_complete' => $document->documents_complete,
                'completion_rate' => $this->getCompletionRate($document),
            ]
        ], 201);
    }

    public function download($id): JsonResponse
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document non trouvé'
            ], 404);
        }

        $files = [
            'cni' => $document->cni_file,
            'passport' => $document->passport_file,
            'visa' => $document->visa_file,
            'vaccination' => $document->vaccination_file,
            'photo' => $document->photo_file,
        ];

        $availableFiles = array_filter($files, function ($file) {
            return $file && Storage::disk('public')->exists($file);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'pilgrim_name' => $document->pilgrim->full_name,
                'files' => array_map(function ($file) {
                    return [
                        'filename' => basename($file),
                        'url' => Storage::url($file),
                        'size' => Storage::disk('public')->size($file),
                    ];
                }, $availableFiles)
            ]
        ]);
    }

    public function delete(Request $request, $id): JsonResponse
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'document_type' => 'required|in:cni,passport,visa,vaccination,photo',
        ]);

        $documentType = $validated['document_type'];
        $fileField = $documentType === 'photo' ? 'photo_file' : $documentType . '_file';
        $infoField = $documentType === 'photo' ? null : $documentType;

        // Supprimer le fichier du stockage
        if ($document->$fileField && Storage::disk('public')->exists($document->$fileField)) {
            Storage::disk('public')->delete($document->$fileField);
        }

        // Vider les champs
        $document->$fileField = null;
        if ($infoField) {
            $document->$infoField = null;
        }

        $document->save();
        $document->checkCompleteness();

        return response()->json([
            'success' => true,
            'message' => 'Document supprimé avec succès',
            'data' => [
                'documents_complete' => $document->documents_complete,
                'completion_rate' => $this->getCompletionRate($document),
            ]
        ]);
    }

    public function checkCompleteness($pilgrimId): JsonResponse
    {
        $pilgrim = Pilgrim::with('documents')->find($pilgrimId);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        if (!$pilgrim->documents) {
            return response()->json([
                'success' => true,
                'data' => [
                    'complete' => false,
                    'completion_rate' => 0,
                    'missing_documents' => [
                        'Carte d\'identité nationale',
                        'Passeport',
                        'Photo d\'identité',
                    ],
                    'status' => 'missing'
                ]
            ]);
        }

        $document = $pilgrim->documents;
        $complete = $document->checkCompleteness();

        return response()->json([
            'success' => true,
            'data' => [
                'complete' => $complete,
                'completion_rate' => $this->getCompletionRate($document),
                'missing_documents' => $document->missing_documents,
                'status' => $complete ? 'complete' : 'incomplete'
            ]
        ]);
    }

    public function updateStatus(Request $request, $pilgrimId): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending_review',
            'notes' => 'nullable|string',
        ]);

        $pilgrim = Pilgrim::with('documents')->find($pilgrimId);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        if (!$pilgrim->documents) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun document trouvé pour ce pèlerin'
            ], 404);
        }

        // Ici on pourrait ajouter un champ status et notes au modèle Document
        // Pour l'instant on met à jour le statut du pèlerin
        if ($validated['status'] === 'approved' && $pilgrim->documents->documents_complete) {
            $pilgrim->update(['status' => 'documents_ready']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut des documents mis à jour',
            'data' => [
                'pilgrim_status' => $pilgrim->status,
                'documents_complete' => $pilgrim->documents->documents_complete,
            ]
        ]);
    }

    private function generateFilename(Pilgrim $pilgrim, string $type, string $extension): string
    {
        $pilgrimName = Str::slug($pilgrim->full_name);
        $timestamp = now()->format('Y-m-d_H-i-s');

        return "{$pilgrimName}_{$type}_{$timestamp}.{$extension}";
    }

    private function getCompletionRate(Document $document): int
    {
        $totalFields = ['cni', 'passport', 'photo_file'];
        $optionalFields = ['visa', 'vaccination_certificate']; // Optionnels selon le type de pèlerinage

        $completedRequired = 0;
        foreach ($totalFields as $field) {
            if (!empty($document->$field)) {
                $completedRequired++;
            }
        }

        $completedOptional = 0;
        foreach ($optionalFields as $field) {
            if (!empty($document->$field)) {
                $completedOptional++;
            }
        }

        // Calcul: 80% pour les obligatoires, 20% pour les optionnels
        $requiredRate = ($completedRequired / count($totalFields)) * 80;
        $optionalRate = ($completedOptional / count($optionalFields)) * 20;

        return round($requiredRate + $optionalRate);
    }
}