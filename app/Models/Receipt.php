<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Receipt extends Model
{
    protected $fillable = [
        'payment_id',
        'receipt_number',
        'template_version',
        'generated_by',
        'generated_at',
        'client_data',
        'payment_data',
        'campaign_data',
        'agency_data',
        'notes',
        'is_printed',
        'print_count',
        'last_printed_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'last_printed_at' => 'datetime',
            'client_data' => 'json',
            'payment_data' => 'json',
            'campaign_data' => 'json',
            'agency_data' => 'json',
            'is_printed' => 'boolean',
            'print_count' => 'integer',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // Générer automatiquement le numéro de reçu
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($receipt) {
            if (!$receipt->receipt_number) {
                $receipt->receipt_number = 'REC-' . date('Y') . '-' . str_pad(static::count() + 1, 8, '0', STR_PAD_LEFT);
            }
            if (!$receipt->generated_at) {
                $receipt->generated_at = now();
            }
        });
    }

    // Marquer comme imprimé
    public function markAsPrinted()
    {
        $this->update([
            'is_printed' => true,
            'print_count' => $this->print_count + 1,
            'last_printed_at' => now(),
        ]);
    }

    // Capturer les données au moment de la génération
    public function captureData()
    {
        $payment = $this->payment;
        $pilgrim = $payment->pilgrim;
        $client = $pilgrim->client;
        $campaign = $pilgrim->campaign;

        $this->update([
            'client_data' => [
                'id' => $client->id ?? null,
                'full_name' => $client->full_name ?? $pilgrim->full_name,
                'phone' => $client->phone ?? $pilgrim->phone,
                'email' => $client->email ?? $pilgrim->email,
                'address' => $client->address ?? $pilgrim->address,
            ],
            'payment_data' => [
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'payment_method' => $payment->payment_method,
                'reference' => $payment->reference,
                'status' => $payment->status,
                'total_amount' => $pilgrim->total_amount,
                'paid_amount' => $pilgrim->paid_amount,
                'remaining_amount' => $pilgrim->remaining_amount,
            ],
            'campaign_data' => $campaign ? [
                'name' => $campaign->name,
                'type' => $campaign->type,
                'departure_date' => $campaign->departure_date,
                'return_date' => $campaign->return_date,
                'price_classic' => $campaign->price_classic,
                'price_vip' => $campaign->price_vip,
            ] : null,
            'agency_data' => $this->getAgencySettings(),
        ]);
    }

    // Récupérer les paramètres de l'agence
    private function getAgencySettings()
    {
        return [
            'company_name' => SystemSetting::get('company_name', 'SAFIR'),
            'company_logo' => SystemSetting::get('company_logo'),
            'company_address' => SystemSetting::get('company_address'),
            'company_city' => SystemSetting::get('company_city'),
            'company_phone' => SystemSetting::get('company_phone'),
            'company_email' => SystemSetting::get('company_email'),
            'company_registration' => SystemSetting::get('company_registration'),
            'currency_symbol' => SystemSetting::get('currency_symbol', 'FCFA'),
            'legal_notice' => SystemSetting::get('legal_notice'),
        ];
    }

    // Générer les données pour l'affichage
    public function getDisplayData()
    {
        return [
            'receipt' => $this,
            'payment' => $this->payment_data,
            'client' => $this->client_data,
            'campaign' => $this->campaign_data,
            'agency' => $this->agency_data,
            'generated_by' => $this->generatedBy,
            'generated_at' => $this->generated_at,
        ];
    }

    // Scopes
    public function scopeByTemplate($query, $template)
    {
        return $query->where('template_version', $template);
    }

    public function scopePrinted($query)
    {
        return $query->where('is_printed', true);
    }

    public function scopeNotPrinted($query)
    {
        return $query->where('is_printed', false);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('generated_at', [$startDate, $endDate]);
    }

    // Templates disponibles
    public static function getAvailableTemplates()
    {
        return [
            'classic' => 'Reçu Classique',
            'modern' => 'Reçu Moderne (V2)',
            'summary' => 'Récapitulatif Client',
            'compact' => 'Reçu Compact',
            'detailed' => 'Reçu Détaillé',
        ];
    }

    // Statistiques des reçus
    public static function getStats()
    {
        return [
            'total_generated' => static::count(),
            'printed_today' => static::whereDate('generated_at', today())->count(),
            'total_printed' => static::where('is_printed', true)->count(),
            'most_used_template' => static::groupBy('template_version')
                ->selectRaw('template_version, count(*) as count')
                ->orderBy('count', 'desc')
                ->first()?->template_version,
        ];
    }
}