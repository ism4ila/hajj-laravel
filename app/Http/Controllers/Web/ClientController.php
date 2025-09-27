<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\CameroonRegionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $clients = $query->with(['pilgrims.campaign'])
                        ->orderBy('lastname')
                        ->orderBy('firstname')
                        ->paginate(20);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = CameroonRegionService::getRegions();
        return view('clients.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'required|string|max:20|unique:clients,phone',
            'email' => 'nullable|email|unique:clients,email',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:255|unique:clients,passport_number',
            'passport_expiry_date' => 'nullable|date|after:today',
            'nationality' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $client = Client::create($validated);

        // Si on vient de l'ajout d'un pèlerin, retourner vers l'ajout avec le client sélectionné
        if ($request->has('return_to_pilgrim') && $request->get('campaign_id')) {
            return redirect()->route('pilgrims.create', [
                'campaign' => $request->get('campaign_id'),
                'client_id' => $client->id
            ])->with('success', "Client {$client->firstname} {$client->lastname} créé avec succès. Vous pouvez maintenant finaliser l'inscription du pèlerin.");
        }

        return redirect()->route('clients.show', $client)
                        ->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['pilgrims.campaign', 'pilgrims.payments']);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $regions = CameroonRegionService::getRegions();
        return view('clients.edit', compact('client', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => ['required', 'string', 'max:20', Rule::unique('clients')->ignore($client->id)],
            'email' => ['nullable', 'email', Rule::unique('clients')->ignore($client->id)],
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'passport_number' => ['nullable', 'string', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'passport_expiry_date' => 'nullable|date|after:today',
            'nationality' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
                        ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->pilgrims()->count() > 0) {
            return redirect()->route('clients.index')
                            ->with('error', 'Impossible de supprimer ce client car il a des pèlerinages associés.');
        }

        $client->delete();

        return redirect()->route('clients.index')
                        ->with('success', 'Client supprimé avec succès.');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('q', $request->get('term', $request->get('search', '')));
        $status = $request->get('status');
        $sort = $request->get('sort', 'name');

        // If this is for table real-time search
        if ($request->has('table_search')) {
            return $this->tableSearch($request);
        }

        // Original search for autocomplete
        if (strlen($searchTerm) < 2) {
            return response()->json([]);
        }

        $clients = Client::active()
                        ->where(function ($q) use ($searchTerm) {
                            $q->where('firstname', 'like', '%' . $searchTerm . '%')
                              ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                              ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                              ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        })
                        ->limit(10)
                        ->get(['id', 'firstname', 'lastname', 'phone', 'email']);

        return response()->json($clients);
    }

    public function tableSearch(Request $request)
    {
        $query = Client::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Apply sorting
        switch ($request->get('sort', 'name')) {
            case 'name':
                $query->orderBy('lastname')->orderBy('firstname');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'old':
                $query->orderBy('created_at', 'asc');
                break;
        }

        $clients = $query->with(['pilgrims.campaign'])->paginate(20);

        // Return HTML view for AJAX
        return response()->json([
            'html' => view('clients.partials.table', compact('clients'))->render(),
            'pagination' => $clients->links()->toHtml(),
            'total' => $clients->total(),
            'showing' => [
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
                'total' => $clients->total()
            ]
        ]);
    }

    public function getDepartments(Request $request)
    {
        $region = $request->get('region');
        $departments = CameroonRegionService::getDepartments($region);

        return response()->json($departments);
    }

    public function toggleStatus(Request $request, Client $client)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $client->update([
            'is_active' => $request->is_active
        ]);

        $message = $client->is_active
            ? 'Client activé avec succès.'
            : 'Client désactivé avec succès.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $client->is_active
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'client_ids' => 'required|json'
        ]);

        $clientIds = json_decode($request->client_ids, true);
        $clients = Client::whereIn('id', $clientIds);

        $message = '';
        $success = true;

        try {
            switch ($request->action) {
                case 'activate':
                    $count = $clients->update(['is_active' => true]);
                    $message = "$count client(s) activé(s) avec succès.";
                    break;

                case 'deactivate':
                    $count = $clients->update(['is_active' => false]);
                    $message = "$count client(s) désactivé(s) avec succès.";
                    break;

                case 'delete':
                    // Vérifier qu'aucun client n'a de pèlerinages
                    $clientsWithPilgrimages = $clients->whereHas('pilgrims')->count();
                    if ($clientsWithPilgrimages > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Impossible de supprimer les clients qui ont des pèlerinages associés."
                        ]);
                    }
                    $count = $clients->delete();
                    $message = "$count client(s) supprimé(s) avec succès.";
                    break;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'opération.'
            ]);
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $clientIds = $request->get('clients');

        // Si des clients spécifiques sont sélectionnés
        if ($clientIds) {
            $clientIds = explode(',', $clientIds);
            $clients = Client::whereIn('id', $clientIds)->with(['pilgrims.campaign'])->get();
        } else {
            // Exporter tous les clients avec les mêmes filtres que la page
            $query = Client::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'like', '%' . $search . '%')
                      ->orWhere('lastname', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            $clients = $query->with(['pilgrims.campaign'])->get();
        }

        $fileName = 'clients_export_' . date('Y-m-d_H-i-s');

        switch ($format) {
            case 'excel':
                return $this->exportExcel($clients, $fileName);
            case 'pdf':
                return $this->exportPDF($clients, $fileName);
            case 'csv':
                return $this->exportCSV($clients, $fileName);
            default:
                return redirect()->back()->with('error', 'Format d\'export non supporté.');
        }
    }

    private function exportExcel($clients, $fileName)
    {
        // Pour l'instant, on va générer un CSV simple
        return $this->exportCSV($clients, $fileName);
    }

    private function exportPDF($clients, $fileName)
    {
        // Générer un HTML simple pour le PDF
        $html = view('clients.exports.pdf', compact('clients'))->render();

        // Pour l'instant, on retourne le HTML directement
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '.html"');
    }

    private function exportCSV($clients, $fileName)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.csv"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Prénom',
                'Nom',
                'Sexe',
                'Date de naissance',
                'Âge',
                'Téléphone',
                'Email',
                'Adresse',
                'Contact d\'urgence',
                'Téléphone d\'urgence',
                'Numéro de passeport',
                'Expiration passeport',
                'Nationalité',
                'Région',
                'Département',
                'Statut',
                'Nombre de pèlerinages',
                'Date de création'
            ]);

            // Données
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->firstname,
                    $client->lastname,
                    $client->gender === 'male' ? 'Homme' : 'Femme',
                    $client->date_of_birth ? $client->date_of_birth->format('d/m/Y') : '',
                    $client->age ?? '',
                    $client->phone ?? '',
                    $client->email ?? '',
                    $client->address ?? '',
                    $client->emergency_contact ?? '',
                    $client->emergency_phone ?? '',
                    $client->passport_number ?? '',
                    $client->passport_expiry_date ? $client->passport_expiry_date->format('d/m/Y') : '',
                    $client->nationality ?? '',
                    $client->region ?? '',
                    $client->department ?? '',
                    $client->is_active ? 'Actif' : 'Inactif',
                    $client->pilgrims->count(),
                    $client->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
