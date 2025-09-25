<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
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
}