<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        Gate::authorize('manage-settings');

        // Get all settings grouped by category
        $settings = SystemSetting::orderBy('category')
            ->orderBy('setting_key')
            ->get()
            ->groupBy('category');

        // If no settings exist, create default ones
        if ($settings->isEmpty()) {
            $this->createDefaultSettings();
            $settings = SystemSetting::orderBy('category')
                ->orderBy('setting_key')
                ->get()
                ->groupBy('category');
        }

        return view('settings.index', compact('settings'));
    }

    /**
     * Update the specified settings.
     */
    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);

        // Handle logo upload/removal first
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            $logoFileName = basename($logoPath);

            // Update or create logo setting
            $logoSetting = SystemSetting::where('setting_key', 'company_logo')->first();
            if ($logoSetting) {
                // Remove old logo file if exists
                if ($logoSetting->setting_value && \Storage::disk('public')->exists('logos/' . $logoSetting->setting_value)) {
                    \Storage::disk('public')->delete('logos/' . $logoSetting->setting_value);
                }
                $logoSetting->update(['setting_value' => $logoFileName]);
            }
        } elseif ($request->has('remove_logo') && $request->remove_logo) {
            $logoSetting = SystemSetting::where('setting_key', 'company_logo')->first();
            if ($logoSetting && $logoSetting->setting_value) {
                // Remove logo file
                if (\Storage::disk('public')->exists('logos/' . $logoSetting->setting_value)) {
                    \Storage::disk('public')->delete('logos/' . $logoSetting->setting_value);
                }
                $logoSetting->update(['setting_value' => '']);
            }
        }

        foreach ($validated['settings'] as $key => $value) {
            $setting = SystemSetting::where('setting_key', $key)->first();

            if ($setting) {
                // Cast value based on type
                $castedValue = $this->castValueForStorage($value, $setting->setting_type);
                $setting->update(['setting_value' => $castedValue]);
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Create default system settings.
     */
    private function createDefaultSettings(): void
    {
        $defaults = [
            // Company Information
            [
                'setting_key' => 'company_name',
                'setting_value' => 'Agence Hajj & Omra Cameroun',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Nom de l\'agence',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_slogan',
                'setting_value' => 'Votre partenaire de confiance pour le Hajj et la Omra',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Slogan de l\'agence',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_address',
                'setting_value' => 'Douala, Cameroun',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Adresse complète de l\'agence',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_city',
                'setting_value' => 'Douala',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Ville',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_country',
                'setting_value' => 'Cameroun',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Pays',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_phone',
                'setting_value' => '+237 6XX XX XX XX',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Téléphone principal',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_phone2',
                'setting_value' => '',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Téléphone secondaire',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_whatsapp',
                'setting_value' => '+237 6XX XX XX XX',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'WhatsApp',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_email',
                'setting_value' => 'contact@hajjomra-cameroun.cm',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Email principal',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_website',
                'setting_value' => 'www.hajjomra-cameroun.cm',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Site web',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_logo',
                'setting_value' => '',
                'setting_type' => 'file',
                'category' => 'company',
                'description' => 'Logo de l\'agence (pour impressions)',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_registration',
                'setting_value' => '',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Numéro d\'enregistrement',
                'is_public' => true
            ],
            [
                'setting_key' => 'company_license',
                'setting_value' => '',
                'setting_type' => 'string',
                'category' => 'company',
                'description' => 'Licence touristique',
                'is_public' => true
            ],

            // Payment Settings
            [
                'setting_key' => 'default_currency',
                'setting_value' => 'FCFA',
                'setting_type' => 'string',
                'category' => 'payment',
                'description' => 'Devise par défaut',
                'is_public' => false
            ],
            [
                'setting_key' => 'payment_terms',
                'setting_value' => '30% à l\'inscription, 70% avant le départ',
                'setting_type' => 'string',
                'category' => 'payment',
                'description' => 'Conditions de paiement',
                'is_public' => true
            ],
            [
                'setting_key' => 'late_payment_fee',
                'setting_value' => '0',
                'setting_type' => 'number',
                'category' => 'payment',
                'description' => 'Frais de retard (%)',
                'is_public' => false
            ],

            // Notification Settings
            [
                'setting_key' => 'email_notifications',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'category' => 'notifications',
                'description' => 'Notifications par email',
                'is_public' => false
            ],
            [
                'setting_key' => 'sms_notifications',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'category' => 'notifications',
                'description' => 'Notifications par SMS',
                'is_public' => false
            ],
            [
                'setting_key' => 'notification_email',
                'setting_value' => 'admin@example.com',
                'setting_type' => 'string',
                'category' => 'notifications',
                'description' => 'Email de notification',
                'is_public' => false
            ],

            // System Settings
            [
                'setting_key' => 'system_timezone',
                'setting_value' => 'Africa/Douala',
                'setting_type' => 'string',
                'category' => 'system',
                'description' => 'Fuseau horaire',
                'is_public' => false
            ],
            [
                'setting_key' => 'date_format',
                'setting_value' => 'd/m/Y',
                'setting_type' => 'string',
                'category' => 'system',
                'description' => 'Format de date',
                'is_public' => false
            ],
            [
                'setting_key' => 'pagination_limit',
                'setting_value' => '15',
                'setting_type' => 'number',
                'category' => 'system',
                'description' => 'Éléments par page',
                'is_public' => false
            ],
        ];

        foreach ($defaults as $setting) {
            SystemSetting::create($setting);
        }
    }

    /**
     * Cast value for storage based on type.
     */
    private function castValueForStorage($value, $type): string
    {
        return match($type) {
            'boolean' => $value ? '1' : '0',
            'number' => (string) $value,
            'json' => is_array($value) ? json_encode($value) : $value,
            default => (string) $value,
        };
    }
}
