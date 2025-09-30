<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Pilgrim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['pilgrim.campaign', 'pilgrim.client'])
            ->orderBy('created_at', 'desc');

        // Filter by pilgrim
        if ($request->has('pilgrim') && $request->pilgrim !== '') {
            $query->where('pilgrim_id', $request->pilgrim);
        }

        // Filter by client
        if ($request->has('client') && $request->client !== '') {
            $query->whereHas('pilgrim', function ($q) use ($request) {
                $q->where('client_id', $request->client);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('method') && $request->method !== '') {
            $query->where('payment_method', $request->method);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date !== '') {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date !== '') {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($mainQuery) use ($search) {
                $mainQuery->whereHas('pilgrim', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%");
                })->orWhereHas('pilgrim.client', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        // Get data for filters
        $pilgrims = Pilgrim::orderBy('firstname')->get();
        $clients = \App\Models\Client::active()->orderBy('firstname')->get();

        // Payment statistics
        $stats = [
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'today_payments' => Payment::where('status', 'completed')
                ->whereDate('payment_date', today())->sum('amount'),
            'count_payments' => Payment::where('status', 'completed')->count(),
        ];

        return view('payments.index', compact('payments', 'pilgrims', 'clients', 'stats'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request): View
    {
        Gate::authorize('manage-payments');

        // Get pilgrim if specified
        $pilgrim = null;
        if ($request->has('pilgrim')) {
            $pilgrim = Pilgrim::with('campaign')->find($request->pilgrim);
        }

        // Get all pilgrims with remaining payments (using dynamic calculation)
        $pilgrims = Pilgrim::with(['campaign', 'payments'])
            ->orderBy('firstname')
            ->get()
            ->filter(function ($pilgrim) {
                return $pilgrim->remaining_amount > 0;
            })
            ->values();

        return view('payments.create', compact('pilgrim', 'pilgrims'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-payments');

        $validated = $request->validate([
            'pilgrim_id' => 'required|exists:pilgrims,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer,card',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ], [
            'pilgrim_id.required' => 'Le pèlerin est obligatoire.',
            'pilgrim_id.exists' => 'Le pèlerin sélectionné n\'existe pas.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_date.required' => 'La date de paiement est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
        ]);

        $pilgrim = Pilgrim::find($validated['pilgrim_id']);

        // Check if payment amount doesn't exceed remaining amount
        if ($validated['amount'] > $pilgrim->remaining_amount) {
            return back()->withErrors([
                'amount' => 'Le montant ne peut pas dépasser le montant restant à payer (' .
                           number_format($pilgrim->remaining_amount, 0, ',', ' ') . ' FCFA).'
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $pilgrim) {
            // Create payment - les montants sont maintenant calculés dynamiquement
            $payment = Payment::create($validated);
        });

        return redirect()->route('payments.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['pilgrim.campaign']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment): View
    {
        Gate::authorize('manage-payments');

        $payment->load(['pilgrim.campaign']);
        $pilgrims = Pilgrim::with('campaign')->orderBy('firstname')->get();

        return view('payments.edit', compact('payment', 'pilgrims'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        Gate::authorize('manage-payments');

        $validated = $request->validate([
            'pilgrim_id' => 'required|exists:pilgrims,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer,card',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $oldStatus = $payment->status;
        $oldAmount = $payment->amount;
        $pilgrim = Pilgrim::find($validated['pilgrim_id']);

        DB::transaction(function () use ($validated, $payment) {
            // Update payment - les montants sont calculés dynamiquement
            $payment->update($validated);
        });

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        Gate::authorize('manage-payments');

        DB::transaction(function () use ($payment) {
            // Delete payment - les montants sont calculés dynamiquement
            $payment->delete();
        });

        return redirect()->route('payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Generate receipt for a payment.
     */
    public function generateReceipt(Payment $payment)
    {
        Gate::authorize('view-reports');

        $payment->load(['pilgrim.campaign']);

        // Get all payments for this pilgrim (payment history)
        $allPayments = Payment::where('pilgrim_id', $payment->pilgrim_id)
            ->orderBy('payment_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get agency settings for the receipt
        $agencySettings = \App\Models\SystemSetting::whereIn('setting_key', [
            'company_name', 'company_slogan', 'company_address', 'company_city', 'company_country',
            'company_phone', 'company_phone2', 'company_whatsapp',
            'company_email', 'company_website', 'company_logo', 'default_currency', 'currency_symbol',
            'company_registration', 'company_license', 'legal_notice', 'terms_conditions',
            'payment_terms', 'timezone', 'language'
        ])->pluck('setting_value', 'setting_key')->toArray();

        // Get current serving user
        $servingUser = auth()->user();

        // Utiliser le template compact par défaut
        $template = request()->get('template', 'compact'); // Options: compact, premium, receipt

        $viewName = match($template) {
            'compact' => 'payments.receipt-compact',
            'premium' => 'payments.receipt-premium',
            'receipt' => 'payments.receipt',
            default => 'payments.receipt-compact',
        };

        $pdf = \App\Facades\PDF::loadView($viewName, compact('payment', 'allPayments', 'agencySettings', 'servingUser'));

        // Generate clean filename without special characters
        $cleanFirstname = \Str::slug($payment->pilgrim->firstname, '-');
        $cleanLastname = \Str::slug($payment->pilgrim->lastname, '-');
        $receiptNumber = str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $date = now()->format('Y-m-d');

        $filename = "Recu_Paiement_{$receiptNumber}_{$cleanFirstname}_{$cleanLastname}_{$date}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Get payment statistics.
     */
    public function stats(Request $request)
    {
        $stats = [
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'today_payments' => Payment::where('status', 'completed')
                ->whereDate('payment_date', today())->sum('amount'),
            'monthly_payments' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'payments_by_method' => Payment::where('status', 'completed')
                ->select('payment_method', DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get(),
        ];

        return response()->json($stats);
    }
}