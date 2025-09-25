<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class PilgrimController extends Controller
{
    /**
     * Display a listing of pilgrims.
     */
    public function index(Request $request): View
    {
        $query = Pilgrim::with(['campaign'])
            ->orderBy('created_at', 'desc');

        // Filter by campaign
        if ($request->has('campaign') && $request->campaign !== '') {
            $query->where('campaign_id', $request->campaign);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender !== '') {
            $query->where('gender', $request->gender);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $pilgrims = $query->paginate(15)->withQueryString();

        // Get campaigns for filter dropdown
        $campaigns = Campaign::orderBy('name')->get();

        return view('pilgrims.index', compact('pilgrims', 'campaigns'));
    }

    /**
     * Show the form for creating a new pilgrim.
     */
    public function create(Request $request): View
    {
        Gate::authorize('manage-pilgrims');

        // Get active campaigns
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Pre-select campaign if provided
        $selectedCampaign = null;
        if ($request->has('campaign')) {
            $selectedCampaign = Campaign::find($request->campaign);
        }

        return view('pilgrims.create', compact('campaigns', 'selectedCampaign'));
    }

    /**
     * Store a newly created pilgrim in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-pilgrims');

        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'category' => 'required|in:classic,vip',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
        ], [
            'campaign_id.required' => 'La campagne est obligatoire.',
            'campaign_id.exists' => 'La campagne sélectionnée n\'existe pas.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.in' => 'La catégorie doit être classique ou VIP.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'gender.required' => 'Le genre est obligatoire.',
            'gender.in' => 'Le genre doit être homme ou femme.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'emergency_contact.required' => 'Le contact d\'urgence est obligatoire.',
            'emergency_phone.required' => 'Le téléphone d\'urgence est obligatoire.',
        ]);

        // Set default status
        $validated['status'] = 'pending';

        $pilgrim = Pilgrim::create($validated);

        return redirect()->route('pilgrims.show', $pilgrim)
            ->with('success', 'Pèlerin inscrit avec succès.');
    }

    /**
     * Display the specified pilgrim.
     */
    public function show(Pilgrim $pilgrim): View
    {
        $pilgrim->load(['campaign']);

        // Get payments for this pilgrim (latest 5)
        $payments = $pilgrim->payments()
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Get documents for this pilgrim (latest 6)
        $documents = $pilgrim->documents()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('pilgrims.show', compact('pilgrim', 'payments', 'documents'));
    }

    /**
     * Show the form for editing the specified pilgrim.
     */
    public function edit(Pilgrim $pilgrim): View
    {
        Gate::authorize('manage-pilgrims');

        $campaigns = Campaign::orderBy('name')->get();

        return view('pilgrims.edit', compact('pilgrim', 'campaigns'));
    }

    /**
     * Update the specified pilgrim in storage.
     */
    public function update(Request $request, Pilgrim $pilgrim): RedirectResponse
    {
        Gate::authorize('manage-pilgrims');

        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,active,completed,cancelled',
        ], [
            'campaign_id.required' => 'La campagne est obligatoire.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'gender.required' => 'Le genre est obligatoire.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'emergency_contact.required' => 'Le contact d\'urgence est obligatoire.',
            'emergency_phone.required' => 'Le téléphone d\'urgence est obligatoire.',
            'total_amount.required' => 'Le montant total est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
        ]);

        // Calculate remaining amount
        $paidAmount = $validated['paid_amount'] ?? 0;
        $validated['paid_amount'] = $paidAmount;
        $validated['remaining_amount'] = $validated['total_amount'] - $paidAmount;

        $pilgrim->update($validated);

        return redirect()->route('pilgrims.show', $pilgrim)
            ->with('success', 'Informations du pèlerin mises à jour avec succès.');
    }

    /**
     * Remove the specified pilgrim from storage.
     */
    public function destroy(Pilgrim $pilgrim): RedirectResponse
    {
        Gate::authorize('manage-pilgrims');

        // Check if pilgrim has payments or documents
        // This will be implemented when we have those relationships

        $pilgrimName = $pilgrim->firstname . ' ' . $pilgrim->lastname;
        $pilgrim->delete();

        return redirect()->route('pilgrims.index')
            ->with('success', "Pèlerin {$pilgrimName} supprimé avec succès.");
    }

    /**
     * Export pilgrims to Excel.
     */
    public function exportExcel(Request $request)
    {
        Gate::authorize('export-data');

        // This will be implemented later
        return redirect()->back()
            ->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    /**
     * Import pilgrims from Excel.
     */
    public function importExcel(Request $request)
    {
        Gate::authorize('manage-pilgrims');

        // This will be implemented later
        return redirect()->back()
            ->with('info', 'Fonctionnalité d\'import en cours de développement.');
    }
}