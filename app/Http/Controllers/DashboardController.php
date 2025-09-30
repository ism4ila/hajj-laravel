<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Document;
use App\Models\Payment;
use App\Models\Pilgrim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index(): View
    {
        // Get key statistics
        $stats = [
            'total_pilgrims' => Pilgrim::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'pending_documents' => $this->getPendingDocumentsCount(),
        ];

        // Recent data
        $recentPilgrims = Pilgrim::with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['pilgrim'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get pilgrims with incomplete payments (using subquery for remaining amount)
        $incompletePayments = Pilgrim::with(['campaign', 'payments'])
            ->whereHas('campaign')
            ->get()
            ->filter(function ($pilgrim) {
                return $pilgrim->remaining_amount > 0;
            })
            ->sortByDesc('remaining_amount')
            ->take(5);

        // Campaign statistics
        $campaignStats = Campaign::select('name', 'type')
            ->withCount('pilgrims')
            ->orderBy('pilgrims_count', 'desc')
            ->take(5)
            ->get();

        // Monthly payment trends (last 6 months) - Will be implemented later
        $monthlyPayments = collect();

        // Payment status distribution - Will be implemented later
        $paymentStatusStats = collect();

        // Document completion rate
        $documentStats = [
            'total_required' => Pilgrim::count() * 4, // Assuming 4 required documents per pilgrim
            'completed' => Document::distinct('pilgrim_id')->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'recentPilgrims',
            'recentPayments',
            'incompletePayments',
            'campaignStats',
            'monthlyPayments',
            'paymentStatusStats',
            'documentStats'
        ));
    }

    /**
     * Get count of pilgrims with pending documents.
     */
    private function getPendingDocumentsCount(): int
    {
        return Pilgrim::leftJoin('documents', 'pilgrims.id', '=', 'documents.pilgrim_id')
            ->where(function($query) {
                $query->whereNull('documents.id')
                      ->orWhere('documents.documents_complete', false)
                      ->orWhereNull('documents.documents_complete');
            })
            ->distinct('pilgrims.id')
            ->count('pilgrims.id');
    }

    /**
     * Get quick actions data for the dashboard.
     */
    public function quickActions(): array
    {
        return [
            [
                'title' => 'Nouveau Pèlerin',
                'description' => 'Ajouter un nouveau pèlerin',
                'icon' => 'fas fa-user-plus',
                'route' => route('pilgrims.create'),
                'color' => 'primary'
            ],
            [
                'title' => 'Nouvelle Campagne',
                'description' => 'Créer une nouvelle campagne',
                'icon' => 'fas fa-flag',
                'route' => route('campaigns.create'),
                'color' => 'success'
            ],
            [
                'title' => 'Enregistrer Paiement',
                'description' => 'Ajouter un nouveau paiement',
                'icon' => 'fas fa-credit-card',
                'route' => route('payments.create'),
                'color' => 'info'
            ],
            [
                'title' => 'Rapports',
                'description' => 'Voir les rapports détaillés',
                'icon' => 'fas fa-chart-bar',
                'route' => route('reports.index'),
                'color' => 'warning'
            ],
        ];
    }

    /**
     * Global search across all entities.
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'clients' => [],
                'pilgrims' => [],
                'campaigns' => [],
                'payments' => []
            ]);
        }

        // Search clients
        $clients = Client::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'title' => $client->full_name,
                    'subtitle' => $client->phone,
                    'url' => route('clients.show', $client),
                    'type' => 'client',
                    'icon' => 'fas fa-user'
                ];
            });

        // Search pilgrims
        $pilgrims = Pilgrim::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('passport_number', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->with('campaign')
            ->limit(5)
            ->get()
            ->map(function ($pilgrim) {
                return [
                    'id' => $pilgrim->id,
                    'title' => $pilgrim->full_name,
                    'subtitle' => $pilgrim->campaign->name ?? 'Aucune campagne',
                    'url' => route('pilgrims.show', $pilgrim),
                    'type' => 'pilgrim',
                    'icon' => 'fas fa-user-friends'
                ];
            });

        // Search campaigns
        $campaigns = Campaign::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->name,
                    'subtitle' => $campaign->type . ' - ' . $campaign->year,
                    'url' => route('campaigns.show', $campaign),
                    'type' => 'campaign',
                    'icon' => 'fas fa-flag'
                ];
            });

        // Search payments (by amount or reference)
        $payments = Payment::where('reference', 'LIKE', "%{$query}%")
            ->orWhere('amount', 'LIKE', "%{$query}%")
            ->orWhere('method', 'LIKE', "%{$query}%")
            ->with('pilgrim')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'title' => 'Paiement ' . number_format($payment->amount, 0, ',', ' ') . ' FCFA',
                    'subtitle' => $payment->pilgrim->full_name ?? 'Pèlerin inconnu',
                    'url' => route('payments.show', $payment),
                    'type' => 'payment',
                    'icon' => 'fas fa-credit-card'
                ];
            });

        return response()->json([
            'clients' => $clients,
            'pilgrims' => $pilgrims,
            'campaigns' => $campaigns,
            'payments' => $payments,
            'total' => $clients->count() + $pilgrims->count() + $campaigns->count() + $payments->count()
        ]);
    }

    /**
     * Display notifications page.
     */
    public function notifications(): View
    {
        // Mock notifications data - will be replaced with real data later
        $notifications = collect([
            [
                'id' => 1,
                'title' => 'Nouveau pèlerin inscrit',
                'message' => 'Ahmed Ben Ali s\'est inscrit pour la campagne Hajj 2024',
                'type' => 'success',
                'icon' => 'fas fa-user-plus',
                'created_at' => now()->subMinutes(5),
                'read' => false,
                'url' => route('pilgrims.index')
            ],
            [
                'id' => 2,
                'title' => 'Paiement reçu',
                'message' => 'Paiement de 15,000 FCFA reçu de Fatima Zahra',
                'type' => 'success',
                'icon' => 'fas fa-credit-card',
                'created_at' => now()->subHour(),
                'read' => false,
                'url' => route('payments.index')
            ],
            [
                'id' => 3,
                'title' => 'Document manquant',
                'message' => 'Passeport requis pour Fatima Zahra',
                'type' => 'warning',
                'icon' => 'fas fa-file-alt',
                'created_at' => now()->subHours(2),
                'read' => false,
                'url' => route('pilgrims.index')
            ],
            [
                'id' => 4,
                'title' => 'Campagne terminée',
                'message' => 'La campagne Omra Mars 2024 est maintenant terminée',
                'type' => 'info',
                'icon' => 'fas fa-flag',
                'created_at' => now()->subDay(),
                'read' => true,
                'url' => route('campaigns.index')
            ],
            [
                'id' => 5,
                'title' => 'Rappel de paiement',
                'message' => 'Mohamed Ali a un solde impayé de 200,000 FCFA',
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'created_at' => now()->subDays(2),
                'read' => true,
                'url' => route('payments.index')
            ]
        ]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get notifications for API (real-time).
     */
    public function getNotifications()
    {
        // Mock real-time notifications - this would normally come from database
        $notifications = [
            [
                'id' => 1,
                'title' => 'Nouveau pèlerin inscrit',
                'message' => 'Ahmed Ben Ali s\'est inscrit pour la campagne Hajj 2024',
                'type' => 'success',
                'icon' => 'fas fa-user-plus',
                'created_at' => now()->subMinutes(5)->toISOString(),
                'read' => false,
                'url' => route('pilgrims.index')
            ],
            [
                'id' => 2,
                'title' => 'Paiement reçu',
                'message' => 'Paiement de 15,000 FCFA reçu de Fatima Zahra',
                'type' => 'success',
                'icon' => 'fas fa-credit-card',
                'created_at' => now()->subHour()->toISOString(),
                'read' => false,
                'url' => route('payments.index')
            ],
            [
                'id' => 3,
                'title' => 'Document manquant',
                'message' => 'Passeport requis pour Fatima Zahra',
                'type' => 'warning',
                'icon' => 'fas fa-file-alt',
                'created_at' => now()->subHours(2)->toISOString(),
                'read' => false,
                'url' => route('pilgrims.index')
            ]
        ];

        // Simulate occasional new notifications
        if (rand(1, 10) > 7) {
            array_unshift($notifications, [
                'id' => rand(100, 999),
                'title' => 'Nouvelle notification',
                'message' => 'Une nouvelle activité vient de se produire',
                'type' => 'info',
                'icon' => 'fas fa-info-circle',
                'created_at' => now()->toISOString(),
                'read' => false,
                'url' => '#'
            ]);
        }

        return response()->json([
            'notifications' => array_slice($notifications, 0, 5),
            'total' => count($notifications),
            'unread' => collect($notifications)->where('read', false)->count(),
            'timestamp' => now()->toISOString()
        ]);
    }
}