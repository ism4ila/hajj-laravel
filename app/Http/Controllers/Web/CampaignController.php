<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request): View
    {
        $query = Campaign::withCount(['pilgrims'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->paginate(15)->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        Gate::authorize('manage-campaigns');

        return view('campaigns.create');
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-campaigns');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hajj,omra',
            'description' => 'nullable|string',
            'year_hijri' => 'required|integer|min:1400',
            'year_gregorian' => 'required|integer|min:2020',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'quota' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Le nom de la campagne est obligatoire.',
            'type.required' => 'Le type de campagne est obligatoire.',
            'type.in' => 'Le type doit être Hajj ou Omra.',
            'year_hijri.required' => 'L\'année hijri est obligatoire.',
            'year_gregorian.required' => 'L\'année grégorienne est obligatoire.',
            'departure_date.required' => 'La date de départ est obligatoire.',
            'return_date.required' => 'La date de retour est obligatoire.',
            'return_date.after' => 'La date de retour doit être après la date de départ.',
            'quota.min' => 'Le quota doit être au moins 1.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
        ]);

        $validated['status'] = $request->has('is_active') ? 'active' : 'inactive';

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign): View
    {
        $campaign->load(['pilgrims.payments', 'pilgrims.documents']);

        $stats = [
            'total_pilgrims' => $campaign->pilgrims->count(),
            'total_payments' => $campaign->pilgrims->flatMap->payments->sum('amount'),
            'completed_documents' => $campaign->pilgrims->filter(function ($pilgrim) {
                return $pilgrim->documents->count() >= 4; // Assuming 4 required documents
            })->count(),
            'pending_documents' => $campaign->pilgrims->filter(function ($pilgrim) {
                return $pilgrim->documents->count() < 4;
            })->count(),
        ];

        // Recent activity
        $recentPilgrims = $campaign->pilgrims()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPayments = $campaign->pilgrims()
            ->join('payments', 'pilgrims.id', '=', 'payments.pilgrim_id')
            ->select('payments.*', 'pilgrims.first_name', 'pilgrims.last_name')
            ->orderBy('payments.created_at', 'desc')
            ->take(5)
            ->get();

        return view('campaigns.show', compact('campaign', 'stats', 'recentPilgrims', 'recentPayments'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign): View
    {
        Gate::authorize('edit-campaign', $campaign);

        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        Gate::authorize('edit-campaign', $campaign);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hajj,omra',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_pilgrims' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Le nom de la campagne est obligatoire.',
            'type.required' => 'Le type de campagne est obligatoire.',
            'type.in' => 'Le type doit être Hajj ou Omra.',
            'start_date.required' => 'La date de début est obligatoire.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'max_pilgrims.min' => 'Le nombre maximum de pèlerins doit être au moins 1.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campagne mise à jour avec succès.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        Gate::authorize('delete-campaign', $campaign);

        // Check if campaign has pilgrims
        if ($campaign->pilgrims()->count() > 0) {
            return redirect()->route('campaigns.index')
                ->with('error', 'Impossible de supprimer une campagne qui a des pèlerins inscrits.');
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne supprimée avec succès.');
    }

    /**
     * Activate the specified campaign.
     */
    public function activate(Campaign $campaign): RedirectResponse
    {
        Gate::authorize('manage-campaigns');

        $campaign->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Campagne activée avec succès.');
    }

    /**
     * Deactivate the specified campaign.
     */
    public function deactivate(Campaign $campaign): RedirectResponse
    {
        Gate::authorize('manage-campaigns');

        $campaign->update(['status' => 'inactive']);

        return redirect()->back()
            ->with('success', 'Campagne désactivée avec succès.');
    }
}