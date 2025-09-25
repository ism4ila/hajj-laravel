<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Campaign::query();

        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('year') && $request->year) {
            $query->where('year_hijri', $request->year)
                  ->orWhere('year_gregorian', $request->year);
        }

        $campaigns = $query->with('pilgrims:id,campaign_id')
                          ->orderBy('created_at', 'desc')
                          ->paginate($request->per_page ?? 15);

        $campaigns->getCollection()->transform(function ($campaign) {
            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'year_hijri' => $campaign->year_hijri,
                'year_gregorian' => $campaign->year_gregorian,
                'price' => $campaign->price,
                'quota' => $campaign->quota,
                'departure_date' => $campaign->departure_date?->format('Y-m-d'),
                'return_date' => $campaign->return_date?->format('Y-m-d'),
                'status' => $campaign->status,
                'pilgrims_count' => $campaign->pilgrims->count(),
                'available_places' => $campaign->available_places,
                'created_at' => $campaign->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $campaigns->items(),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $campaign = Campaign::with(['pilgrims' => function ($query) {
            $query->select('id', 'campaign_id', 'first_name', 'last_name', 'status')
                  ->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'year_hijri' => $campaign->year_hijri,
                'year_gregorian' => $campaign->year_gregorian,
                'price' => $campaign->price,
                'quota' => $campaign->quota,
                'departure_date' => $campaign->departure_date?->format('Y-m-d'),
                'return_date' => $campaign->return_date?->format('Y-m-d'),
                'description' => $campaign->description,
                'status' => $campaign->status,
                'pilgrims_count' => $campaign->pilgrims->count(),
                'available_places' => $campaign->available_places,
                'pilgrims' => $campaign->pilgrims->map(function ($pilgrim) {
                    return [
                        'id' => $pilgrim->id,
                        'full_name' => $pilgrim->first_name . ' ' . $pilgrim->last_name,
                        'status' => $pilgrim->status,
                    ];
                }),
                'created_at' => $campaign->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $campaign->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hajj,omra',
            'year_hijri' => 'required|integer|min:1400|max:1500',
            'year_gregorian' => 'required|integer|min:1980|max:2100',
            'price' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        $campaign = Campaign::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Campagne créée avec succès',
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'status' => $campaign->status,
                'created_at' => $campaign->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:hajj,omra',
            'year_hijri' => 'sometimes|required|integer|min:1400|max:1500',
            'year_gregorian' => 'sometimes|required|integer|min:1980|max:2100',
            'price' => 'sometimes|required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'departure_date' => 'sometimes|required|date',
            'return_date' => 'sometimes|required|date|after:departure_date',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,active,completed,cancelled',
        ]);

        $campaign->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Campagne mise à jour avec succès',
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'status' => $campaign->status,
                'updated_at' => $campaign->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        if ($campaign->pilgrims()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer une campagne avec des pèlerins inscrits'
            ], 400);
        }

        $campaign->delete();

        return response()->json([
            'success' => true,
            'message' => 'Campagne supprimée avec succès'
        ]);
    }

    public function activate($id): JsonResponse
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        $newStatus = $campaign->status === 'active' ? 'draft' : 'active';
        $campaign->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 'active' ? 'Campagne activée' : 'Campagne désactivée',
            'data' => [
                'id' => $campaign->id,
                'status' => $campaign->status,
            ]
        ]);
    }

    public function statistics($id): JsonResponse
    {
        $campaign = Campaign::with(['pilgrims'])->find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        $pilgrims = $campaign->pilgrims;
        $statusCounts = $pilgrims->groupBy('status')->map->count();

        return response()->json([
            'success' => true,
            'data' => [
                'campaign' => [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'type' => $campaign->type,
                ],
                'total_pilgrims' => $pilgrims->count(),
                'quota' => $campaign->quota,
                'available_places' => $campaign->available_places,
                'status_breakdown' => [
                    'pending' => $statusCounts->get('pending', 0),
                    'confirmed' => $statusCounts->get('confirmed', 0),
                    'paid' => $statusCounts->get('paid', 0),
                    'documents_ready' => $statusCounts->get('documents_ready', 0),
                    'completed' => $statusCounts->get('completed', 0),
                    'cancelled' => $statusCounts->get('cancelled', 0),
                ],
                'occupancy_rate' => $campaign->quota ? round(($pilgrims->count() / $campaign->quota) * 100, 2) : null,
            ]
        ]);
    }
}