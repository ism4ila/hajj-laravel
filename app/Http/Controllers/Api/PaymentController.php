<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Payment::query();

        if ($request->has('pilgrim_id') && $request->pilgrim_id) {
            $query->where('pilgrim_id', $request->pilgrim_id);
        }

        if ($request->has('payment_method') && $request->payment_method) {
            $query->byMethod($request->payment_method);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        if ($request->has('receipt_number') && $request->receipt_number) {
            $query->where('receipt_number', 'like', "%{$request->receipt_number}%");
        }

        $payments = $query->with([
            'pilgrim:id,firstname,lastname,campaign_id',
            'pilgrim.campaign:id,name,type',
            'createdBy:id,name'
        ])
        ->orderBy('payment_date', 'desc')
        ->paginate($request->per_page ?? 15);

        $payments->getCollection()->transform(function ($payment) {
            return [
                'id' => $payment->id,
                'pilgrim' => $payment->pilgrim ? [
                    'id' => $payment->pilgrim->id,
                    'full_name' => $payment->pilgrim->full_name,
                    'campaign' => $payment->pilgrim->campaign?->name,
                ] : null,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'payment_method' => $payment->payment_method,
                'reference' => $payment->reference,
                'receipt_number' => $payment->receipt_number,
                'created_by' => $payment->createdBy?->name,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'total_amount' => Payment::when($request->pilgrim_id, function ($q) use ($request) {
                    $q->where('pilgrim_id', $request->pilgrim_id);
                })->sum('amount'),
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $payment = Payment::with([
            'pilgrim:id,firstname,lastname,campaign_id,total_amount,paid_amount,remaining_amount',
            'pilgrim.campaign:id,name,type,price',
            'createdBy:id,name,email'
        ])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'pilgrim' => $payment->pilgrim ? [
                    'id' => $payment->pilgrim->id,
                    'full_name' => $payment->pilgrim->full_name,
                    'campaign' => $payment->pilgrim->campaign ? [
                        'name' => $payment->pilgrim->campaign->name,
                        'type' => $payment->pilgrim->campaign->type,
                        'price' => $payment->pilgrim->campaign->price,
                    ] : null,
                    'amounts' => [
                        'total' => $payment->pilgrim->total_amount,
                        'paid' => $payment->pilgrim->paid_amount,
                        'remaining' => $payment->pilgrim->remaining_amount,
                    ]
                ] : null,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'payment_method' => $payment->payment_method,
                'reference' => $payment->reference,
                'receipt_number' => $payment->receipt_number,
                'notes' => $payment->notes,
                'created_by' => $payment->createdBy ? [
                    'id' => $payment->createdBy->id,
                    'name' => $payment->createdBy->name,
                    'email' => $payment->createdBy->email,
                ] : null,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $payment->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pilgrim_id' => 'required|exists:pilgrims,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,check,card',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $pilgrim = Pilgrim::find($validated['pilgrim_id']);

        if ($validated['amount'] > $pilgrim->remaining_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Le montant dépasse le solde restant (' . $pilgrim->remaining_amount . ')'
            ], 400);
        }

        $validated['created_by'] = Auth::id();

        $payment = Payment::create($validated);

        // Mise à jour automatique du statut du pèlerin si montant complet payé
        $pilgrim->refresh();
        if ($pilgrim->remaining_amount == 0 && $pilgrim->status === 'confirmed') {
            $pilgrim->update(['status' => 'paid']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paiement enregistré avec succès',
            'data' => [
                'id' => $payment->id,
                'receipt_number' => $payment->receipt_number,
                'amount' => $payment->amount,
                'pilgrim_remaining' => $pilgrim->remaining_amount,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0.01',
            'payment_date' => 'sometimes|required|date|before_or_equal:today',
            'payment_method' => 'sometimes|required|in:cash,bank_transfer,check,card',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $oldAmount = $payment->amount;
        $payment->update($validated);

        // Recalculer les montants si le montant a changé
        if (isset($validated['amount']) && $validated['amount'] != $oldAmount) {
            $payment->pilgrim->updateAmounts();
        }

        return response()->json([
            'success' => true,
            'message' => 'Paiement mis à jour avec succès',
            'data' => [
                'id' => $payment->id,
                'receipt_number' => $payment->receipt_number,
                'amount' => $payment->amount,
                'updated_at' => $payment->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }

        $pilgrim = $payment->pilgrim;
        $payment->delete();

        // Recalculer les montants après suppression
        $pilgrim->updateAmounts();

        return response()->json([
            'success' => true,
            'message' => 'Paiement annulé avec succès'
        ]);
    }

    public function receipt($id): JsonResponse
    {
        $payment = Payment::with([
            'pilgrim:id,firstname,lastname,phone,campaign_id',
            'pilgrim.campaign:id,name,type',
            'createdBy:id,name'
        ])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_info' => [
                    'number' => $payment->receipt_number,
                    'date' => $payment->payment_date->format('d/m/Y'),
                    'created_at' => $payment->created_at->format('d/m/Y H:i'),
                ],
                'pilgrim' => [
                    'full_name' => $payment->pilgrim->full_name,
                    'phone' => $payment->pilgrim->phone,
                    'campaign' => $payment->pilgrim->campaign?->name,
                    'campaign_type' => $payment->pilgrim->campaign?->type,
                ],
                'payment' => [
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'reference' => $payment->reference,
                    'notes' => $payment->notes,
                ],
                'created_by' => $payment->createdBy?->name,
                'company_info' => [
                    'name' => config('app.name'),
                    'address' => 'Adresse de l\'agence',
                    'phone' => 'Téléphone agence',
                    'email' => 'email@agence.com',
                ]
            ]
        ]);
    }

    public function pilgrims($pilgrimId): JsonResponse
    {
        $pilgrim = Pilgrim::with(['payments' => function ($query) {
            $query->with('createdBy:id,name')
                  ->orderBy('payment_date', 'desc');
        }])->find($pilgrimId);

        if (!$pilgrim) {
            return response()->json([
                'success' => false,
                'message' => 'Pèlerin non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pilgrim' => [
                    'id' => $pilgrim->id,
                    'full_name' => $pilgrim->full_name,
                    'total_amount' => $pilgrim->total_amount,
                    'paid_amount' => $pilgrim->paid_amount,
                    'remaining_amount' => $pilgrim->remaining_amount,
                ],
                'payments' => $pilgrim->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'payment_method' => $payment->payment_method,
                        'reference' => $payment->reference,
                        'receipt_number' => $payment->receipt_number,
                        'created_by' => $payment->createdBy?->name,
                        'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'summary' => [
                    'total_payments' => $pilgrim->payments->count(),
                    'total_amount' => $pilgrim->payments->sum('amount'),
                    'last_payment_date' => $pilgrim->payments->first()?->payment_date?->format('Y-m-d'),
                ]
            ]
        ]);
    }

    public function statistics(): JsonResponse
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');
        $thisYear = now()->format('Y');

        return response()->json([
            'success' => true,
            'data' => [
                'today' => [
                    'count' => Payment::whereDate('payment_date', $today)->count(),
                    'amount' => Payment::whereDate('payment_date', $today)->sum('amount'),
                ],
                'this_month' => [
                    'count' => Payment::whereYear('payment_date', now()->year)
                                    ->whereMonth('payment_date', now()->month)
                                    ->count(),
                    'amount' => Payment::whereYear('payment_date', now()->year)
                                     ->whereMonth('payment_date', now()->month)
                                     ->sum('amount'),
                ],
                'this_year' => [
                    'count' => Payment::whereYear('payment_date', now()->year)->count(),
                    'amount' => Payment::whereYear('payment_date', now()->year)->sum('amount'),
                ],
                'by_method' => Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                                    ->groupBy('payment_method')
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        return [$item->payment_method => [
                                            'count' => $item->count,
                                            'total' => $item->total,
                                        ]];
                                    }),
                'recent_payments' => Payment::with('pilgrim:id,firstname,lastname')
                                          ->orderBy('created_at', 'desc')
                                          ->limit(10)
                                          ->get()
                                          ->map(function ($payment) {
                                              return [
                                                  'id' => $payment->id,
                                                  'amount' => $payment->amount,
                                                  'pilgrim' => $payment->pilgrim?->full_name,
                                                  'receipt_number' => $payment->receipt_number,
                                                  'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                                              ];
                                          }),
            ]
        ]);
    }
}