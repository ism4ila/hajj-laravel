<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pilgrim;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PilgrimController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Pilgrim::query();

        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        if ($request->has('gender') && $request->gender) {
            $query->byGender($request->gender);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $pilgrims = $query->with(['campaign:id,name,type', 'documents:id,pilgrim_id,status'])
                         ->orderBy('created_at', 'desc')
                         ->paginate($request->per_page ?? 15);

        $pilgrims->getCollection()->transform(function ($pilgrim) {
            return [
                'id' => $pilgrim->id,
                'full_name' => $pilgrim->full_name,
                'firstname' => $pilgrim->firstname,
                'lastname' => $pilgrim->lastname,
                'gender' => $pilgrim->gender,
                'age' => $pilgrim->age ?? null,
                'phone' => $pilgrim->phone,
                'email' => $pilgrim->email,
                'status' => $pilgrim->status,
                'campaign' => $pilgrim->campaign ? [
                    'id' => $pilgrim->campaign->id,
                    'name' => $pilgrim->campaign->name,
                    'type' => $pilgrim->campaign->type,
                ] : null,
                'total_amount' => $pilgrim->total_amount,
                'paid_amount' => $pilgrim->paid_amount,
                'remaining_amount' => $pilgrim->remaining_amount,
                'documents_status' => $pilgrim->documents->status ?? 'missing',
                'created_at' => $pilgrim->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $pilgrims->items(),
            'meta' => [
                'current_page' => $pilgrims->currentPage(),
                'last_page' => $pilgrims->lastPage(),
                'per_page' => $pilgrims->perPage(),
                'total' => $pilgrims->total(),
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $pilgrim = Pilgrim::with([
            'campaign:id,name,type,price,departure_date,return_date',
            'documents',
            'payments:id,pilgrim_id,amount,payment_date,method,reference,created_by'
        ])->find($id);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pilgrim->id,
                'firstname' => $pilgrim->firstname,
                'lastname' => $pilgrim->lastname,
                'full_name' => $pilgrim->full_name,
                'gender' => $pilgrim->gender,
                'date_of_birth' => $pilgrim->date_of_birth?->format('Y-m-d'),
                'age' => $pilgrim->age ?? null,
                'phone' => $pilgrim->phone,
                'email' => $pilgrim->email,
                'address' => $pilgrim->address,
                'emergency_contact' => $pilgrim->emergency_contact,
                'emergency_phone' => $pilgrim->emergency_phone,
                'status' => $pilgrim->status,
                'campaign' => $pilgrim->campaign ? [
                    'id' => $pilgrim->campaign->id,
                    'name' => $pilgrim->campaign->name,
                    'type' => $pilgrim->campaign->type,
                    'price' => $pilgrim->campaign->price,
                    'departure_date' => $pilgrim->campaign->departure_date?->format('Y-m-d'),
                    'return_date' => $pilgrim->campaign->return_date?->format('Y-m-d'),
                ] : null,
                'amounts' => [
                    'total' => $pilgrim->total_amount,
                    'paid' => $pilgrim->paid_amount,
                    'remaining' => $pilgrim->remaining_amount,
                ],
                'documents' => $pilgrim->documents ? [
                    'id' => $pilgrim->documents->id,
                    'status' => $pilgrim->documents->status,
                    'photo_path' => $pilgrim->documents->photo_path,
                    'cni_path' => $pilgrim->documents->cni_path,
                    'passport_path' => $pilgrim->documents->passport_path,
                    'visa_path' => $pilgrim->documents->visa_path,
                    'vaccination_path' => $pilgrim->documents->vaccination_path,
                ] : null,
                'payments' => $pilgrim->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'payment_date' => $payment->payment_date,
                        'method' => $payment->method,
                        'reference' => $payment->reference,
                        'created_by' => $payment->created_by,
                    ];
                }),
                'created_at' => $pilgrim->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $pilgrim->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
        ]);

        $campaign = Campaign::find($validated['campaign_id']);
        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        if ($campaign->available_places !== null && $campaign->available_places <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune place disponible pour cette campagne'
            ], 400);
        }

        $validated['total_amount'] = $campaign->price;
        $validated['paid_amount'] = 0;
        $validated['remaining_amount'] = $campaign->price;
        $validated['status'] = 'pending';

        $pilgrim = Pilgrim::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pèlerin enregistré avec succès',
            'data' => [
                'id' => $pilgrim->id,
                'full_name' => $pilgrim->full_name,
                'campaign' => $campaign->name,
                'status' => $pilgrim->status,
                'created_at' => $pilgrim->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $pilgrim = Pilgrim::find($id);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'campaign_id' => 'sometimes|required|exists:campaigns,id',
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'date_of_birth' => 'sometimes|required|date|before:today',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'sometimes|required|string',
            'emergency_contact' => 'sometimes|required|string|max:255',
            'emergency_phone' => 'sometimes|required|string|max:20',
        ]);

        if (isset($validated['campaign_id']) && $validated['campaign_id'] !== $pilgrim->campaign_id) {
            $campaign = Campaign::find($validated['campaign_id']);
            $validated['total_amount'] = $campaign->price;
            $validated['remaining_amount'] = $campaign->price - $pilgrim->paid_amount;
        }

        $pilgrim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pèlerin mis à jour avec succès',
            'data' => [
                'id' => $pilgrim->id,
                'full_name' => $pilgrim->full_name,
                'status' => $pilgrim->status,
                'updated_at' => $pilgrim->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $pilgrim = Pilgrim::find($id);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        if ($pilgrim->payments()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer un pèlerin avec des paiements'
            ], 400);
        }

        if ($pilgrim->documents) {
            $pilgrim->documents->delete();
        }

        $pilgrim->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pèlerin supprimé avec succès'
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $pilgrim = Pilgrim::find($id);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,documents_ready,completed,cancelled',
        ]);

        $pilgrim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => [
                'id' => $pilgrim->id,
                'full_name' => $pilgrim->full_name,
                'status' => $pilgrim->status,
                'updated_at' => $pilgrim->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->query;

        $pilgrims = Pilgrim::where(function ($q) use ($query) {
                $q->where('firstname', 'like', "%{$query}%")
                  ->orWhere('lastname', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->with('campaign:id,name,type')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pilgrims->map(function ($pilgrim) {
                return [
                    'id' => $pilgrim->id,
                    'full_name' => $pilgrim->full_name,
                    'phone' => $pilgrim->phone,
                    'email' => $pilgrim->email,
                    'status' => $pilgrim->status,
                    'campaign' => $pilgrim->campaign?->name,
                ];
            })
        ]);
    }

    public function export(Request $request): JsonResponse
    {
        $query = Pilgrim::query();

        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        $pilgrims = $query->with('campaign:id,name,type')->get();

        return response()->json([
            'success' => true,
            'message' => 'Export disponible',
            'data' => $pilgrims->map(function ($pilgrim) {
                return [
                    'ID' => $pilgrim->id,
                    'Nom complet' => $pilgrim->full_name,
                    'Genre' => $pilgrim->gender === 'male' ? 'Homme' : 'Femme',
                    'Date de naissance' => $pilgrim->date_of_birth?->format('Y-m-d'),
                    'Téléphone' => $pilgrim->phone,
                    'Email' => $pilgrim->email,
                    'Adresse' => $pilgrim->address,
                    'Contact urgence' => $pilgrim->emergency_contact,
                    'Téléphone urgence' => $pilgrim->emergency_phone,
                    'Campagne' => $pilgrim->campaign?->name,
                    'Type' => $pilgrim->campaign?->type,
                    'Montant total' => $pilgrim->total_amount,
                    'Montant payé' => $pilgrim->paid_amount,
                    'Reste à payer' => $pilgrim->remaining_amount,
                    'Statut' => $pilgrim->status,
                    'Date inscription' => $pilgrim->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'count' => $pilgrims->count(),
        ]);
    }
}