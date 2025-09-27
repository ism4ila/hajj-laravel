<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PilgrimController extends Controller
{
    /**
     * Display a listing of pilgrims.
     */
    public function index(Request $request): View
    {
        $query = Pilgrim::with(['campaign', 'client'])
            ->orderBy('created_at', 'desc');

        // Filter by campaign
        if ($request->has('campaign') && $request->campaign !== '') {
            $query->where('campaign_id', $request->campaign);
        }

        // Filter by client
        if ($request->has('client') && $request->client !== '') {
            $query->where('client_id', $request->client);
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
            $query->where(function ($mainQuery) use ($search) {
                $mainQuery->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $pilgrims = $query->paginate(15)->withQueryString();

        // Get data for filter dropdowns
        $campaigns = Campaign::orderBy('name')->get();
        $clients = Client::active()->orderBy('firstname')->get();

        return view('pilgrims.index', compact('pilgrims', 'campaigns', 'clients'));
    }

    /**
     * Show the form for creating a new pilgrim.
     */
    public function create(Request $request): View
    {

        // Get active campaigns
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Pre-select campaign if provided
        $selectedCampaign = null;
        if ($request->has('campaign')) {
            $selectedCampaign = Campaign::find($request->campaign);
        }

        // Pre-select client if provided (coming back from client creation)
        $selectedClient = null;
        if ($request->has('client_id')) {
            $selectedClient = Client::find($request->client_id);
        }

        return view('pilgrims.create', compact('campaigns', 'selectedCampaign', 'selectedClient'));
    }

    /**
     * Store a newly created pilgrim in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'client_id' => 'nullable|exists:clients,id',
            'create_client' => 'boolean',
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
            'passport_number' => 'nullable|string|max:255',
            'passport_expiry_date' => 'nullable|date|after:today',
            'nationality' => 'nullable|string|max:255',
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

        // Gérer la création ou sélection du client
        $clientId = null;

        if ($request->filled('client_id')) {
            // Client existant sélectionné
            $clientId = $validated['client_id'];
        } elseif ($request->boolean('create_client') || $request->boolean('create_new_client')) {
            // Créer un nouveau client (soit via formulaire complet, soit via modal)
            if ($request->boolean('create_new_client')) {
                // Validation spéciale pour nouveau client via modal
                $newClientValidated = $request->validate([
                    'new_client_firstname' => 'required|string|max:255',
                    'new_client_lastname' => 'required|string|max:255',
                    'new_client_gender' => 'required|in:male,female',
                    'new_client_date_of_birth' => 'required|date|before:today',
                    'new_client_phone' => 'required|string|max:20|unique:clients,phone',
                    'new_client_email' => 'nullable|email|unique:clients,email',
                ], [
                    'new_client_firstname.required' => 'Le prénom du client est obligatoire.',
                    'new_client_lastname.required' => 'Le nom du client est obligatoire.',
                    'new_client_gender.required' => 'Le genre du client est obligatoire.',
                    'new_client_date_of_birth.required' => 'La date de naissance du client est obligatoire.',
                    'new_client_phone.required' => 'Le téléphone du client est obligatoire.',
                    'new_client_phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
                    'new_client_email.unique' => 'Cette adresse email est déjà utilisée.',
                ]);

                // Nouveau client via modal
                $clientData = [
                    'firstname' => $newClientValidated['new_client_firstname'],
                    'lastname' => $newClientValidated['new_client_lastname'],
                    'gender' => $newClientValidated['new_client_gender'],
                    'date_of_birth' => $newClientValidated['new_client_date_of_birth'],
                    'phone' => $newClientValidated['new_client_phone'],
                    'email' => $newClientValidated['new_client_email'],
                    'nationality' => 'Cameroun', // Par défaut
                    'is_active' => true,
                ];
            } else {
                // Nouveau client via formulaire complet
                $clientData = [
                    'firstname' => $validated['firstname'],
                    'lastname' => $validated['lastname'],
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                    'emergency_contact' => $validated['emergency_contact'],
                    'emergency_phone' => $validated['emergency_phone'],
                    'passport_number' => $validated['passport_number'] ?? null,
                    'passport_expiry_date' => $validated['passport_expiry_date'] ?? null,
                    'nationality' => $validated['nationality'] ?? null,
                ];
            }

            $client = Client::create($clientData);
            $clientId = $client->id;
        }

        // Données du pèlerin
        $pilgrimData = [
            'campaign_id' => $validated['campaign_id'],
            'client_id' => $clientId,
            'category' => $validated['category'],
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_phone' => $validated['emergency_phone'],
            'status' => 'registered',
        ];

        $pilgrim = Pilgrim::create($pilgrimData);

        // Si c'est une requête AJAX (depuis le modal), retourner JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Pèlerin {$pilgrim->firstname} {$pilgrim->lastname} inscrit avec succès dans la campagne.",
                'pilgrim' => $pilgrim
            ]);
        }

        return redirect()->route('campaigns.show', $pilgrim->campaign)
            ->with('success', "Pèlerin {$pilgrim->firstname} {$pilgrim->lastname} inscrit avec succès dans la campagne.");
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

        $campaigns = Campaign::orderBy('name')->get();

        return view('pilgrims.edit', compact('pilgrim', 'campaigns'));
    }

    /**
     * Update the specified pilgrim in storage.
     */
    public function update(Request $request, Pilgrim $pilgrim): RedirectResponse
    {

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
            'status' => 'required|in:registered,documents_pending,documents_complete,paid,cancelled',
        ], [
            'campaign_id.required' => 'La campagne est obligatoire.',
            'category.required' => 'La catégorie est obligatoire.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'gender.required' => 'Le genre est obligatoire.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'emergency_contact.required' => 'Le contact d\'urgence est obligatoire.',
            'emergency_phone.required' => 'Le téléphone d\'urgence est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
        ]);

        $pilgrim->update($validated);

        return redirect()->route('pilgrims.show', $pilgrim)
            ->with('success', 'Informations du pèlerin mises à jour avec succès.');
    }

    /**
     * Remove the specified pilgrim from storage.
     */
    public function destroy(Pilgrim $pilgrim): RedirectResponse
    {

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

        // This will be implemented later
        return redirect()->back()
            ->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    /**
     * Import pilgrims from Excel.
     */
    public function importExcel(Request $request)
    {

        // This will be implemented later
        return redirect()->back()
            ->with('info', 'Fonctionnalité d\'import en cours de développement.');
    }
}