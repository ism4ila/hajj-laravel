<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Pilgrim;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount('pilgrims')
            ->with(['pilgrims.payments'])
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campaigns,name',
            'type' => 'required|in:hajj,omra',
            'description' => 'nullable|string|max:1000',
            'year_hijri' => 'required|integer|min:1400|max:1500',
            'year_gregorian' => 'required|integer|min:2020|max:2050',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
            'price_classic' => 'required|numeric|min:0',
            'price_vip' => 'required|numeric|min:0|gt:price_classic',
            'classic_description' => 'nullable|string|max:500',
            'vip_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,open,closed,active,completed,cancelled',
        ]);

        $campaign = Campaign::create($validated);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', "La campagne '{$campaign->name}' a été créée avec succès.");
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['pilgrims.payments', 'pilgrims.documents']);

        // Calculate financial summary
        $classicPilgrims = $campaign->pilgrims->where('category', 'classic');
        $vipPilgrims = $campaign->pilgrims->where('category', 'vip');
        $totalExpected = ($classicPilgrims->count() * $campaign->price_classic) + ($vipPilgrims->count() * $campaign->price_vip);

        // Calculate total collected more safely
        $totalCollected = 0;
        $totalPayments = 0;
        $pendingPayments = 0;

        foreach ($campaign->pilgrims as $pilgrim) {
            foreach ($pilgrim->payments as $payment) {
                $totalPayments += $payment->amount;
                if ($payment->status === 'completed') {
                    $totalCollected += $payment->amount;
                } elseif ($payment->status === 'pending') {
                    $pendingPayments += $payment->amount;
                }
            }
        }

        $stats = [
            'total_pilgrims' => $campaign->pilgrims->count(),
            'classic_pilgrims' => $classicPilgrims->count(),
            'vip_pilgrims' => $vipPilgrims->count(),
            'total_expected' => $totalExpected,
            'total_collected' => $totalCollected,
            'remaining_amount' => $totalExpected - $totalCollected,
            'collection_percentage' => $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100, 2) : 0,
            'total_payments' => $totalPayments,
            'pending_payments' => $pendingPayments,
            'completed_documents' => $campaign->pilgrims->filter(function ($pilgrim) {
                return isset($pilgrim->documents) && $pilgrim->documents->count() >= 4;
            })->count(),
            'pending_documents' => $campaign->pilgrims->filter(function ($pilgrim) {
                return !isset($pilgrim->documents) || $pilgrim->documents->count() < 4;
            })->count(),
        ];

        $recentPilgrims = $campaign->pilgrims()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::select('payments.*', 'pilgrims.firstname', 'pilgrims.lastname')
            ->join('pilgrims', 'payments.pilgrim_id', '=', 'pilgrims.id')
            ->where('pilgrims.campaign_id', $campaign->id)
            ->where('payments.status', 'completed')
            ->orderBy('payments.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('campaigns.show', compact('campaign', 'stats', 'recentPilgrims', 'recentPayments'));
    }

    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('campaigns', 'name')->ignore($campaign->id)],
            'type' => 'required|in:hajj,omra',
            'description' => 'nullable|string|max:1000',
            'year_hijri' => 'required|integer|min:1400|max:1500',
            'year_gregorian' => 'required|integer|min:2020|max:2050',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'price_classic' => 'required|numeric|min:0',
            'price_vip' => 'required|numeric|min:0|gt:price_classic',
            'classic_description' => 'nullable|string|max:500',
            'vip_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,open,closed,active,completed,cancelled',
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', "La campagne '{$campaign->name}' a été modifiée avec succès.");
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->pilgrims()->count() > 0) {
            return redirect()
                ->route('campaigns.show', $campaign)
                ->with('error', 'Impossible de supprimer une campagne qui contient des pèlerins.');
        }

        $campaignName = $campaign->name;
        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('success', "La campagne '{$campaignName}' a été supprimée avec succès.");
    }

    public function activate(Campaign $campaign)
    {
        $campaign->update(['status' => 'active']);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', "La campagne '{$campaign->name}' a été activée.");
    }

    public function deactivate(Campaign $campaign)
    {
        $campaign->update(['status' => 'closed']);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('warning', "La campagne '{$campaign->name}' a été fermée.");
    }
}
