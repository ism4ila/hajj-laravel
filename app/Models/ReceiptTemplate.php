<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptTemplate extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'template_path',
        'css_styles',
        'is_active',
        'is_default',
        'created_by',
        'settings',
        'preview_image',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'settings' => 'json',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'template_version', 'code');
    }

    // Templates par défaut
    public static function getDefaultTemplates()
    {
        return [
            [
                'name' => 'Reçu Classique',
                'code' => 'classic',
                'description' => 'Template de reçu classique avec design compact et informations essentielles',
                'template_path' => 'payments.receipt',
                'is_active' => true,
                'is_default' => true,
                'settings' => [
                    'show_watermark' => true,
                    'show_signatures' => true,
                    'show_payment_history' => true,
                    'compact_mode' => true,
                ],
            ],
            [
                'name' => 'Reçu Moderne',
                'code' => 'modern',
                'description' => 'Template moderne avec design gradient et mise en page améliorée',
                'template_path' => 'payments.receipt-v2',
                'is_active' => true,
                'is_default' => false,
                'settings' => [
                    'show_watermark' => false,
                    'show_signatures' => true,
                    'show_payment_history' => true,
                    'compact_mode' => false,
                    'use_gradients' => true,
                ],
            ],
            [
                'name' => 'Récapitulatif Client',
                'code' => 'summary',
                'description' => 'Page récapitulative avec nom client et montants très visibles',
                'template_path' => 'payments.client-summary',
                'is_active' => true,
                'is_default' => false,
                'settings' => [
                    'show_watermark' => false,
                    'show_signatures' => false,
                    'show_payment_history' => false,
                    'large_fonts' => true,
                    'highlight_amounts' => true,
                ],
            ],
        ];
    }

    // Initialiser les templates par défaut
    public static function initializeDefaults()
    {
        foreach (self::getDefaultTemplates() as $template) {
            self::firstOrCreate(
                ['code' => $template['code']],
                $template
            );
        }
    }

    // Définir comme template par défaut
    public function setAsDefault()
    {
        // Retirer le statut par défaut des autres templates
        static::where('is_default', true)->update(['is_default' => false]);

        // Définir ce template comme par défaut
        $this->update(['is_default' => true]);
    }

    // Obtenir le template par défaut
    public static function getDefault()
    {
        return static::where('is_default', true)->first()
            ?? static::where('code', 'classic')->first()
            ?? static::first();
    }

    // Générer un reçu avec ce template
    public function generateReceipt($payment, $generatedBy = null)
    {
        $receipt = Receipt::create([
            'payment_id' => $payment->id,
            'template_version' => $this->code,
            'generated_by' => $generatedBy ?? auth()->id(),
        ]);

        $receipt->captureData();

        return $receipt;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // Statistiques d'utilisation
    public function getUsageStats()
    {
        return [
            'total_receipts' => $this->receipts()->count(),
            'receipts_this_month' => $this->receipts()
                ->whereMonth('generated_at', now()->month)
                ->count(),
            'last_used' => $this->receipts()
                ->latest('generated_at')
                ->first()?->generated_at,
        ];
    }

    // Valider les paramètres du template
    public function validateSettings()
    {
        $requiredSettings = [
            'show_watermark' => 'boolean',
            'show_signatures' => 'boolean',
            'show_payment_history' => 'boolean',
        ];

        $settings = $this->settings ?? [];
        $isValid = true;

        foreach ($requiredSettings as $key => $type) {
            if (!isset($settings[$key])) {
                $isValid = false;
                break;
            }
        }

        return $isValid;
    }
}