<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Pilgrim;
use App\Models\Payment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Statistiques générales
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalPilgrims = Pilgrim::count();
        $totalPayments = Payment::sum('amount');

        // Statistiques de l'année en cours
        $thisYearPilgrims = Pilgrim::whereYear('created_at', $currentYear)->count();
        $thisYearPayments = Payment::whereYear('created_at', $currentYear)->sum('amount');

        // Statistiques du mois en cours
        $thisMonthPilgrims = Pilgrim::whereYear('created_at', $currentYear)
                                   ->whereMonth('created_at', $currentMonth)
                                   ->count();
        $thisMonthPayments = Payment::whereYear('created_at', $currentYear)
                                   ->whereMonth('created_at', $currentMonth)
                                   ->sum('amount');

        // Répartition par statut des pèlerins
        $pilgrimsByStatus = Pilgrim::select('status', DB::raw('count(*) as count'))
                                  ->groupBy('status')
                                  ->get()
                                  ->pluck('count', 'status');

        // Répartition par genre
        $pilgrimsByGender = Pilgrim::select('gender', DB::raw('count(*) as count'))
                                  ->groupBy('gender')
                                  ->get()
                                  ->pluck('count', 'gender');

        // Campagnes les plus populaires
        $popularCampaigns = Campaign::withCount('pilgrims')
                                   ->orderBy('pilgrims_count', 'desc')
                                   ->limit(5)
                                   ->get()
                                   ->map(function ($campaign) {
                                       return [
                                           'id' => $campaign->id,
                                           'name' => $campaign->name,
                                           'type' => $campaign->type,
                                           'pilgrims_count' => $campaign->pilgrims_count,
                                           'quota' => $campaign->quota,
                                           'occupancy_rate' => $campaign->quota ?
                                               round(($campaign->pilgrims_count / $campaign->quota) * 100, 2) : null,
                                       ];
                                   });

        // Évolution mensuelle des inscriptions (12 derniers mois)
        $monthlyRegistrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Pilgrim::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->count();

            $monthlyRegistrations[] = [
                'month' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Évolution mensuelle des paiements (12 derniers mois)
        $monthlyPayments = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $amount = Payment::whereYear('payment_date', $date->year)
                            ->whereMonth('payment_date', $date->month)
                            ->sum('amount');

            $monthlyPayments[] = [
                'month' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'amount' => $amount,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_campaigns' => $totalCampaigns,
                    'active_campaigns' => $activeCampaigns,
                    'total_pilgrims' => $totalPilgrims,
                    'total_payments' => $totalPayments,
                ],
                'current_year' => [
                    'pilgrims' => $thisYearPilgrims,
                    'payments' => $thisYearPayments,
                ],
                'current_month' => [
                    'pilgrims' => $thisMonthPilgrims,
                    'payments' => $thisMonthPayments,
                ],
                'pilgrims_by_status' => $pilgrimsByStatus,
                'pilgrims_by_gender' => $pilgrimsByGender,
                'popular_campaigns' => $popularCampaigns,
                'monthly_registrations' => $monthlyRegistrations,
                'monthly_payments' => $monthlyPayments,
            ]
        ]);
    }

    public function campaigns(Request $request): JsonResponse
    {
        $query = Campaign::withCount(['pilgrims']);

        if ($request->has('year') && $request->year) {
            $query->where('year_gregorian', $request->year);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $campaigns = $query->get()->map(function ($campaign) {
            $pilgrims = $campaign->pilgrims()->with('payments')->get();
            $totalRevenue = $pilgrims->sum(function ($pilgrim) {
                return $pilgrim->payments->sum('amount');
            });

            $statusBreakdown = $pilgrims->groupBy('status')->map->count();

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
                'statistics' => [
                    'pilgrims_count' => $campaign->pilgrims_count,
                    'available_places' => $campaign->available_places,
                    'occupancy_rate' => $campaign->quota ?
                        round(($campaign->pilgrims_count / $campaign->quota) * 100, 2) : null,
                    'total_revenue' => $totalRevenue,
                    'expected_revenue' => $campaign->pilgrims_count * $campaign->price,
                    'payment_rate' => $campaign->pilgrims_count > 0 ?
                        round(($totalRevenue / ($campaign->pilgrims_count * $campaign->price)) * 100, 2) : 0,
                    'status_breakdown' => $statusBreakdown,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $campaigns,
            'summary' => [
                'total_campaigns' => $campaigns->count(),
                'total_pilgrims' => $campaigns->sum('statistics.pilgrims_count'),
                'total_revenue' => $campaigns->sum('statistics.total_revenue'),
                'average_occupancy' => $campaigns->where('quota', '>', 0)->avg('statistics.occupancy_rate'),
            ]
        ]);
    }

    public function payments(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Statistiques générales de la période
        $totalPayments = Payment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        $paymentCount = Payment::whereBetween('payment_date', [$startDate, $endDate])->count();
        $averagePayment = $paymentCount > 0 ? $totalPayments / $paymentCount : 0;

        // Répartition par méthode de paiement
        $paymentsByMethod = Payment::whereBetween('payment_date', [$startDate, $endDate])
                                   ->select('payment_method',
                                           DB::raw('COUNT(*) as count'),
                                           DB::raw('SUM(amount) as total'))
                                   ->groupBy('payment_method')
                                   ->get()
                                   ->mapWithKeys(function ($item) {
                                       return [$item->payment_method => [
                                           'count' => $item->count,
                                           'total' => $item->total,
                                           'percentage' => 0, // Sera calculé après
                                       ]];
                                   });

        // Calcul des pourcentages
        foreach ($paymentsByMethod as $method => $data) {
            $paymentsByMethod[$method]['percentage'] = $totalPayments > 0 ?
                round(($data['total'] / $totalPayments) * 100, 2) : 0;
        }

        // Évolution quotidienne de la période
        $dailyPayments = Payment::whereBetween('payment_date', [$startDate, $endDate])
                                ->select(DB::raw('DATE(payment_date) as date'),
                                        DB::raw('COUNT(*) as count'),
                                        DB::raw('SUM(amount) as total'))
                                ->groupBy(DB::raw('DATE(payment_date)'))
                                ->orderBy('date')
                                ->get();

        // Top 10 des paiements les plus importants
        $topPayments = Payment::with(['pilgrim:id,firstname,lastname', 'createdBy:id,name'])
                              ->whereBetween('payment_date', [$startDate, $endDate])
                              ->orderBy('amount', 'desc')
                              ->limit(10)
                              ->get()
                              ->map(function ($payment) {
                                  return [
                                      'id' => $payment->id,
                                      'amount' => $payment->amount,
                                      'pilgrim' => $payment->pilgrim?->full_name,
                                      'payment_method' => $payment->payment_method,
                                      'receipt_number' => $payment->receipt_number,
                                      'payment_date' => $payment->payment_date->format('Y-m-d'),
                                      'created_by' => $payment->createdBy?->name,
                                  ];
                              });

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'summary' => [
                    'total_amount' => $totalPayments,
                    'payment_count' => $paymentCount,
                    'average_payment' => round($averagePayment, 2),
                ],
                'by_method' => $paymentsByMethod,
                'daily_evolution' => $dailyPayments,
                'top_payments' => $topPayments,
            ]
        ]);
    }

    public function pilgrims(Request $request): JsonResponse
    {
        $query = Pilgrim::with(['campaign:id,name,type', 'documents']);

        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $pilgrims = $query->get();

        // Statistiques générales
        $totalPilgrims = $pilgrims->count();
        $averageAge = $pilgrims->whereNotNull('date_of_birth')->avg(function ($pilgrim) {
            return $pilgrim->age;
        });

        // Répartition par statut
        $statusBreakdown = $pilgrims->groupBy('status')->map->count();

        // Répartition par genre
        $genderBreakdown = $pilgrims->groupBy('gender')->map->count();

        // Répartition par campagne
        $campaignBreakdown = $pilgrims->groupBy('campaign.name')->map->count();

        // Répartition par tranche d'âge
        $ageRanges = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0,
        ];

        foreach ($pilgrims as $pilgrim) {
            if ($pilgrim->age) {
                $age = $pilgrim->age;
                if ($age <= 25) $ageRanges['18-25']++;
                elseif ($age <= 35) $ageRanges['26-35']++;
                elseif ($age <= 45) $ageRanges['36-45']++;
                elseif ($age <= 55) $ageRanges['46-55']++;
                elseif ($age <= 65) $ageRanges['56-65']++;
                else $ageRanges['65+']++;
            }
        }

        // Analyse des paiements
        $pilgrims->load(['payments', 'campaign']);
        $fullyPaid = $pilgrims->filter(function($pilgrim) {
            return $pilgrim->remaining_amount <= 0;
        })->count();
        $partiallyPaid = $pilgrims->filter(function($pilgrim) {
            return $pilgrim->paid_amount > 0 && $pilgrim->remaining_amount > 0;
        })->count();
        $unpaid = $pilgrims->filter(function($pilgrim) {
            return $pilgrim->paid_amount <= 0;
        })->count();

        // Analyse des documents
        $documentsComplete = $pilgrims->whereHas('documents', function ($query) {
            $query->where('documents_complete', true);
        })->count();
        $documentsIncomplete = $pilgrims->whereHas('documents', function ($query) {
            $query->where('documents_complete', false);
        })->count();
        $documentsNone = $pilgrims->whereDoesntHave('documents')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_pilgrims' => $totalPilgrims,
                    'average_age' => $averageAge ? round($averageAge, 1) : null,
                ],
                'status_breakdown' => $statusBreakdown,
                'gender_breakdown' => $genderBreakdown,
                'campaign_breakdown' => $campaignBreakdown,
                'age_ranges' => $ageRanges,
                'payment_analysis' => [
                    'fully_paid' => $fullyPaid,
                    'partially_paid' => $partiallyPaid,
                    'unpaid' => $unpaid,
                    'payment_rate' => $totalPilgrims > 0 ?
                        round((($fullyPaid + $partiallyPaid) / $totalPilgrims) * 100, 2) : 0,
                ],
                'documents_analysis' => [
                    'complete' => $documentsComplete,
                    'incomplete' => $documentsIncomplete,
                    'none' => $documentsNone,
                    'completion_rate' => $totalPilgrims > 0 ?
                        round(($documentsComplete / $totalPilgrims) * 100, 2) : 0,
                ],
            ]
        ]);
    }

    public function documents(): JsonResponse
    {
        $totalPilgrims = Pilgrim::count();
        $withDocuments = Document::count();
        $completeDocuments = Document::where('documents_complete', true)->count();

        // Analyse par type de document
        $documentTypes = [
            'cni' => Document::whereNotNull('cni')->count(),
            'passport' => Document::whereNotNull('passport')->count(),
            'visa' => Document::whereNotNull('visa')->count(),
            'vaccination' => Document::whereNotNull('vaccination_certificate')->count(),
            'photo' => Document::whereNotNull('photo_file')->count(),
        ];

        // Documents par statut de pèlerin
        $documentsByPilgrimStatus = Pilgrim::with('documents')
                                          ->get()
                                          ->groupBy('status')
                                          ->map(function ($pilgrims, $status) {
                                              $withDocs = $pilgrims->whereNotNull('documents')->count();
                                              $completeDocs = $pilgrims->filter(function ($pilgrim) {
                                                  return $pilgrim->documents && $pilgrim->documents->documents_complete;
                                              })->count();

                                              return [
                                                  'total' => $pilgrims->count(),
                                                  'with_documents' => $withDocs,
                                                  'complete_documents' => $completeDocs,
                                                  'completion_rate' => $pilgrims->count() > 0 ?
                                                      round(($completeDocs / $pilgrims->count()) * 100, 2) : 0,
                                              ];
                                          });

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_pilgrims' => $totalPilgrims,
                    'with_documents' => $withDocuments,
                    'complete_documents' => $completeDocuments,
                    'completion_rate' => $totalPilgrims > 0 ?
                        round(($completeDocuments / $totalPilgrims) * 100, 2) : 0,
                ],
                'document_types' => $documentTypes,
                'by_pilgrim_status' => $documentsByPilgrimStatus,
                'missing_documents' => [
                    'no_documents' => $totalPilgrims - $withDocuments,
                    'incomplete_documents' => $withDocuments - $completeDocuments,
                ]
            ]
        ]);
    }

    public function export(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:campaigns,pilgrims,payments,dashboard',
            'format' => 'in:json,csv',
            'filters' => 'array',
        ]);

        $type = $validated['type'];
        $format = $validated['format'] ?? 'json';

        switch ($type) {
            case 'dashboard':
                $data = $this->dashboard()->getData()->data;
                break;
            case 'campaigns':
                $data = $this->campaigns($request)->getData()->data;
                break;
            case 'payments':
                $data = $this->payments($request)->getData()->data;
                break;
            case 'pilgrims':
                $data = $this->pilgrims($request)->getData()->data;
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Type de rapport non supporté'
                ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Données du rapport exportées',
            'data' => $data,
            'export_info' => [
                'type' => $type,
                'format' => $format,
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'total_records' => is_array($data) || is_countable($data) ? count($data) : 1,
            ]
        ]);
    }
}