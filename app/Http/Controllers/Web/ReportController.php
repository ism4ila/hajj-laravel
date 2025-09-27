<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Pilgrim;
use App\Models\Payment;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(): View
    {
        Gate::authorize('view-reports');

        // Get summary statistics
        $stats = $this->getGeneralStatistics();

        // Get recent activities
        $recentPayments = Payment::with(['pilgrim.client', 'pilgrim.campaign'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentPilgrims = Pilgrim::with(['campaign', 'client'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get monthly trends
        $monthlyTrends = $this->getMonthlyTrends();

        return view('reports.index', compact('stats', 'recentPayments', 'recentPilgrims', 'monthlyTrends'));
    }

    /**
     * Campaign reports.
     */
    public function campaigns(): View
    {
        Gate::authorize('view-reports');

        $campaigns = Campaign::withCount(['pilgrims'])
            ->withSum([
                'pilgrims as total_paid' => function($query) {
                    $query->join('payments', 'pilgrims.id', '=', 'payments.pilgrim_id')
                          ->where('payments.status', 'completed');
                }
            ], 'payments.amount')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate additional metrics for each campaign
        foreach ($campaigns as $campaign) {
            $expectedRevenue = $campaign->pilgrims_count * ($campaign->price ?? 0);
            $totalPaid = $campaign->total_paid ?? 0;

            $campaign->total_revenue = $expectedRevenue;
            $campaign->total_paid = $totalPaid;
            $campaign->total_remaining = $expectedRevenue - $totalPaid;
            $campaign->completion_rate = $expectedRevenue > 0
                ? round(($totalPaid / $expectedRevenue) * 100, 2)
                : 0;
        }

        return view('reports.campaigns', compact('campaigns'));
    }

    /**
     * Payment reports.
     */
    public function payments(Request $request): View
    {
        Gate::authorize('view-reports');

        $query = Payment::with(['pilgrim.campaign', 'pilgrim.client']);

        // Apply filters
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        // Payment statistics
        $paymentStats = [
            'total_amount' => $query->where('status', 'completed')->sum('amount'),
            'total_count' => $query->where('status', 'completed')->count(),
            'pending_amount' => $query->where('status', 'pending')->sum('amount'),
            'cancelled_amount' => $query->where('status', 'cancelled')->sum('amount'),
            'by_method' => $query->where('status', 'completed')
                ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('payment_method')
                ->get()
        ];

        return view('reports.payments', compact('payments', 'paymentStats'));
    }

    /**
     * Pilgrim reports.
     */
    public function pilgrims(): View
    {
        Gate::authorize('view-reports');

        // Calculer les statistiques de paiement
        $pilgrims = Pilgrim::with(['payments', 'campaign'])->get();

        $fullyPaid = 0;
        $partiallyPaid = 0;
        $unpaid = 0;

        foreach ($pilgrims as $pilgrim) {
            $totalPaid = $pilgrim->payments->where('status', 'completed')->sum('amount');
            $expectedAmount = 0;

            if ($pilgrim->campaign) {
                // Utiliser le prix unique de la campagne
                $expectedAmount = $pilgrim->campaign->price ?? 0;
            }

            if ($totalPaid >= $expectedAmount && $expectedAmount > 0) {
                $fullyPaid++;
            } elseif ($totalPaid > 0) {
                $partiallyPaid++;
            } else {
                $unpaid++;
            }
        }

        $pilgrimStats = [
            'total_pilgrims' => $pilgrims->count(),
            'by_category' => Pilgrim::select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->get(),
            'by_status' => Pilgrim::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get(),
            'by_campaign' => Pilgrim::with('campaign')
                ->select('campaign_id', DB::raw('COUNT(*) as count'))
                ->groupBy('campaign_id')
                ->get(),
            'payment_status' => [
                'fully_paid' => $fullyPaid,
                'partially_paid' => $partiallyPaid,
                'unpaid' => $unpaid,
            ]
        ];

        return view('reports.pilgrims', compact('pilgrimStats'));
    }

    /**
     * Client reports.
     */
    public function clients(Request $request): View
    {
        Gate::authorize('view-reports');

        $query = Client::withCount(['pilgrims'])
            ->withSum([
                'pilgrims as total_revenue' => function($q) {
                    $q->join('payments', 'pilgrims.id', '=', 'payments.pilgrim_id')
                      ->where('payments.status', 'completed');
                }
            ], 'payments.amount');

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }

        if ($request->filled('min_pilgrimages')) {
            $query->whereHas('pilgrims', function($q) use ($request) {
                $q->havingRaw('COUNT(*) >= ?', [$request->min_pilgrimages]);
            });
        }

        $clients = $query->get();

        // Calculate client statistics
        $clientStats = [
            'total_clients' => $clients->count(),
            'active_clients' => $clients->where('is_active', true)->count(),
            'clients_with_multiple_pilgrimages' => $clients->where('pilgrims_count', '>', 1)->count(),
            'total_revenue_from_clients' => $clients->sum('total_revenue'),
            'avg_pilgrimages_per_client' => $clients->count() > 0
                ? round($clients->sum('pilgrims_count') / $clients->count(), 2)
                : 0,
            'top_clients' => $clients->sortByDesc('total_revenue')->take(10),
            'by_nationality' => $clients->groupBy('nationality')->map->count(),
            'clients_by_pilgrimage_count' => $clients->groupBy(function($client) {
                $count = $client->pilgrims_count;
                if ($count == 0) return '0 pèlerinages';
                if ($count == 1) return '1 pèlerinage';
                if ($count <= 3) return '2-3 pèlerinages';
                return '4+ pèlerinages';
            })->map->count()
        ];

        return view('reports.clients', compact('clientStats', 'clients'));
    }

    /**
     * Document reports.
     */
    public function documents(): View
    {
        Gate::authorize('view-reports');

        $pilgrims = Pilgrim::with('campaign')->get();

        $documentStats = [
            'total_pilgrims' => $pilgrims->count(),
            'documents_complete' => 0,
            'documents_incomplete' => 0,
            'by_document_type' => []
        ];

        $documentTypes = ['passport', 'visa', 'vaccination', 'photo', 'medical'];

        foreach ($documentTypes as $type) {
            $documentStats['by_document_type'][$type] = [
                'complete' => 0,
                'incomplete' => 0
            ];
        }

        foreach ($pilgrims as $pilgrim) {
            $documents = json_decode($pilgrim->documents ?? '{}', true);
            $allComplete = true;

            foreach ($documentTypes as $type) {
                $isComplete = isset($documents[$type]) && !empty($documents[$type]);

                if ($isComplete) {
                    $documentStats['by_document_type'][$type]['complete']++;
                } else {
                    $documentStats['by_document_type'][$type]['incomplete']++;
                    $allComplete = false;
                }
            }

            if ($allComplete) {
                $documentStats['documents_complete']++;
            } else {
                $documentStats['documents_incomplete']++;
            }
        }

        return view('reports.documents', compact('documentStats', 'pilgrims'));
    }

    /**
     * Export reports based on type.
     */
    public function export(Request $request)
    {
        Gate::authorize('export-data');

        $validated = $request->validate([
            'type' => 'required|in:campaigns,payments,pilgrims,documents',
            'format' => 'required|in:pdf,excel',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        switch ($validated['type']) {
            case 'campaigns':
                return $this->exportCampaigns($validated);
            case 'payments':
                return $this->exportPayments($validated);
            case 'pilgrims':
                return $this->exportPilgrims($validated);
            case 'documents':
                return $this->exportDocuments($validated);
        }
    }

    /**
     * Get general statistics for dashboard.
     */
    private function getGeneralStatistics(): array
    {
        return Cache::remember('general_statistics', 300, function () { // Cache for 5 minutes
            return [
                'total_campaigns' => Campaign::count(),
                'active_campaigns' => Campaign::where('status', 'active')->count(),
                'total_pilgrims' => Pilgrim::count(),
                'total_clients' => Client::count(),
                'active_clients' => Client::where('is_active', true)->count(),
                'clients_with_multiple_pilgrimages' => Client::withCount('pilgrims')
                    ->having('pilgrims_count', '>', 1)
                    ->count(),
                'total_payments' => Payment::where('status', 'completed')->sum('amount'),
                'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
                'this_month_revenue' => Payment::where('status', 'completed')
                    ->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year)
                    ->sum('amount'),
                'avg_payment' => Payment::where('status', 'completed')->avg('amount'),
                'pilgrims_by_status' => Pilgrim::select('status', DB::raw('COUNT(*) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
                'pilgrims_with_clients' => Pilgrim::whereNotNull('client_id')->count(),
                'pilgrims_without_clients' => Pilgrim::whereNull('client_id')->count()
            ];
        });
    }

    /**
     * Get monthly trends data.
     */
    private function getMonthlyTrends(): array
    {
        $months = [];
        $revenues = [];
        $pilgrimCounts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthRevenue = Payment::where('status', 'completed')
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            $revenues[] = $monthRevenue;

            $monthPilgrims = Pilgrim::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $pilgrimCounts[] = $monthPilgrims;
        }

        return [
            'months' => $months,
            'revenues' => $revenues,
            'pilgrims' => $pilgrimCounts
        ];
    }

    /**
     * Export campaigns data.
     */
    private function exportCampaigns(array $params)
    {
        if ($params['format'] === 'excel') {
            return Excel::download(new \App\Exports\CampaignsExport(), 'campaigns-' . now()->format('Y-m-d') . '.xlsx');
        }

        // PDF export
        $campaigns = Campaign::withCount(['pilgrims'])->get();
        $agencySettings = SystemSetting::whereIn('setting_key', [
            'company_name', 'company_address', 'company_phone', 'company_email', 'company_logo'
        ])->pluck('setting_value', 'setting_key')->toArray();

        $pdf = \App\Facades\PDF::loadView('reports.exports.campaigns-pdf',
            compact('campaigns', 'agencySettings'));

        $filename = 'Rapport_Campagnes_' . now()->format('Y-m-d_H-i') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export payments data.
     */
    private function exportPayments(array $params)
    {
        $query = Payment::with(['pilgrim.campaign', 'pilgrim.client']);

        if (isset($params['from_date']) && $params['from_date']) {
            $query->whereDate('payment_date', '>=', $params['from_date']);
        }

        if (isset($params['to_date']) && $params['to_date']) {
            $query->whereDate('payment_date', '<=', $params['to_date']);
        }

        if ($params['format'] === 'excel') {
            return Excel::download(new \App\Exports\PaymentsExport($params), 'payments-' . now()->format('Y-m-d') . '.xlsx');
        }

        // PDF export
        $payments = $query->orderBy('payment_date', 'desc')->get();
        $agencySettings = SystemSetting::whereIn('setting_key', [
            'company_name', 'company_address', 'company_phone', 'company_email', 'company_logo'
        ])->pluck('setting_value', 'setting_key')->toArray();

        $pdf = \App\Facades\PDF::loadView('reports.exports.payments-pdf-modern',
            compact('payments', 'agencySettings', 'params'));

        $filename = 'Rapport_Paiements_' . now()->format('Y-m-d_H-i') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export pilgrims data.
     */
    private function exportPilgrims(array $params)
    {
        if ($params['format'] === 'excel') {
            return Excel::download(new \App\Exports\PilgrimsExport(), 'pilgrims-' . now()->format('Y-m-d') . '.xlsx');
        }

        // PDF export
        $pilgrims = Pilgrim::with('campaign')->orderBy('firstname')->get();
        $agencySettings = SystemSetting::whereIn('setting_key', [
            'company_name', 'company_address', 'company_phone', 'company_email', 'company_logo'
        ])->pluck('setting_value', 'setting_key')->toArray();

        $pdf = \App\Facades\PDF::loadView('reports.exports.pilgrims-pdf',
            compact('pilgrims', 'agencySettings'));

        $filename = 'Rapport_Pelerins_' . now()->format('Y-m-d_H-i') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export documents data.
     */
    private function exportDocuments(array $params)
    {
        if ($params['format'] === 'excel') {
            return Excel::download(new \App\Exports\DocumentsExport(), 'documents-' . now()->format('Y-m-d') . '.xlsx');
        }

        // PDF export
        $pilgrims = Pilgrim::with('campaign')->get();
        $agencySettings = SystemSetting::whereIn('setting_key', [
            'company_name', 'company_address', 'company_phone', 'company_email', 'company_logo'
        ])->pluck('setting_value', 'setting_key')->toArray();

        $pdf = \App\Facades\PDF::loadView('reports.exports.documents-pdf',
            compact('pilgrims', 'agencySettings'));

        $filename = 'Rapport_Documents_' . now()->format('Y-m-d_H-i') . '.pdf';
        return $pdf->download($filename);
    }
}
