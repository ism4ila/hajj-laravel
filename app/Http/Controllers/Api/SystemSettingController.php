<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemSettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_view')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $query = SystemSetting::query();

        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        if ($request->has('public_only') && $request->public_only) {
            $query->public();
        }

        $settings = $query->orderBy('category')
                         ->orderBy('setting_key')
                         ->get();

        $groupedSettings = $settings->groupBy('category');

        return response()->json([
            'success' => true,
            'data' => $groupedSettings->map(function ($categorySettings) {
                return $categorySettings->map(function ($setting) {
                    return [
                        'id' => $setting->id,
                        'key' => $setting->setting_key,
                        'value' => $this->castValue($setting->setting_value, $setting->setting_type),
                        'raw_value' => $setting->setting_value,
                        'type' => $setting->setting_type,
                        'category' => $setting->category,
                        'description' => $setting->description,
                        'is_public' => $setting->is_public,
                        'updated_at' => $setting->updated_at->format('Y-m-d H:i:s'),
                    ];
                })->values();
            })
        ]);
    }

    public function show($key): JsonResponse
    {
        // Vérifier les permissions pour les paramètres privés
        $setting = SystemSetting::where('setting_key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètre non trouvé'
            ], 404);
        }

        if (!$setting->is_public && !Auth::user()->hasPermission('settings_view')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $setting->id,
                'key' => $setting->setting_key,
                'value' => $this->castValue($setting->setting_value, $setting->setting_type),
                'raw_value' => $setting->setting_value,
                'type' => $setting->setting_type,
                'category' => $setting->category,
                'description' => $setting->description,
                'is_public' => $setting->is_public,
                'created_at' => $setting->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $setting->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.type' => 'sometimes|in:string,number,boolean,json',
        ]);

        $updatedSettings = [];
        $errors = [];

        foreach ($validated['settings'] as $settingData) {
            try {
                $key = $settingData['key'];
                $value = $settingData['value'];
                $type = $settingData['type'] ?? 'string';

                // Validation spécifique selon le type
                if (!$this->validateSettingValue($value, $type)) {
                    $errors[$key] = "Valeur invalide pour le type {$type}";
                    continue;
                }

                // Conversion de la valeur selon le type
                $processedValue = $this->processSettingValue($value, $type);

                $setting = SystemSetting::updateOrCreate(
                    ['setting_key' => $key],
                    [
                        'setting_value' => $processedValue,
                        'setting_type' => $type,
                    ]
                );

                $updatedSettings[] = [
                    'key' => $key,
                    'value' => $this->castValue($setting->setting_value, $setting->setting_type),
                    'type' => $type,
                ];

                // Vider le cache pour ce paramètre
                Cache::forget("setting.{$key}");

            } catch (\Exception $e) {
                $errors[$key] = 'Erreur lors de la mise à jour: ' . $e->getMessage();
            }
        }

        if (!empty($errors) && empty($updatedSettings)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun paramètre n\'a pu être mis à jour',
                'errors' => $errors
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => count($updatedSettings) . ' paramètre(s) mis à jour avec succès',
            'data' => $updatedSettings,
            'errors' => $errors
        ]);
    }

    public function categories(): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_view')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $categories = SystemSetting::select('category')
                                 ->selectRaw('COUNT(*) as settings_count')
                                 ->groupBy('category')
                                 ->orderBy('category')
                                 ->get();

        // Définition des métadonnées des catégories
        $categoryMeta = [
            'general' => [
                'display_name' => 'Général',
                'description' => 'Paramètres généraux de l\'application',
                'icon' => 'settings',
            ],
            'company' => [
                'display_name' => 'Entreprise',
                'description' => 'Informations sur l\'agence',
                'icon' => 'building',
            ],
            'notifications' => [
                'display_name' => 'Notifications',
                'description' => 'Configuration des notifications',
                'icon' => 'bell',
            ],
            'security' => [
                'display_name' => 'Sécurité',
                'description' => 'Paramètres de sécurité',
                'icon' => 'shield',
            ],
            'payment' => [
                'display_name' => 'Paiements',
                'description' => 'Configuration des paiements',
                'icon' => 'credit-card',
            ],
            'email' => [
                'display_name' => 'Email',
                'description' => 'Configuration email',
                'icon' => 'mail',
            ],
        ];

        $categoriesWithMeta = $categories->map(function ($category) use ($categoryMeta) {
            $meta = $categoryMeta[$category->category] ?? [
                'display_name' => ucfirst($category->category),
                'description' => 'Paramètres ' . $category->category,
                'icon' => 'folder',
            ];

            return [
                'name' => $category->category,
                'display_name' => $meta['display_name'],
                'description' => $meta['description'],
                'icon' => $meta['icon'],
                'settings_count' => $category->settings_count,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesWithMeta
        ]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $validated = $request->validate([
            'category' => 'required|string',
            'settings' => 'required|array',
        ]);

        $category = $validated['category'];
        $settings = $validated['settings'];
        $updated = 0;
        $errors = [];

        foreach ($settings as $key => $value) {
            try {
                // Récupérer le paramètre existant pour garder son type
                $existingSetting = SystemSetting::where('setting_key', $key)->first();
                $type = $existingSetting ? $existingSetting->setting_type : 'string';

                if (!$this->validateSettingValue($value, $type)) {
                    $errors[$key] = "Valeur invalide pour le type {$type}";
                    continue;
                }

                $processedValue = $this->processSettingValue($value, $type);

                SystemSetting::updateOrCreate(
                    ['setting_key' => $key],
                    [
                        'setting_value' => $processedValue,
                        'setting_type' => $type,
                        'category' => $category,
                    ]
                );

                Cache::forget("setting.{$key}");
                $updated++;

            } catch (\Exception $e) {
                $errors[$key] = 'Erreur: ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updated} paramètre(s) mis à jour dans la catégorie {$category}",
            'updated' => $updated,
            'errors' => $errors
        ]);
    }

    public function getPublic(): JsonResponse
    {
        $publicSettings = SystemSetting::public()
                                      ->get()
                                      ->mapWithKeys(function ($setting) {
                                          return [
                                              $setting->setting_key => $this->castValue(
                                                  $setting->setting_value,
                                                  $setting->setting_type
                                              )
                                          ];
                                      });

        return response()->json([
            'success' => true,
            'data' => $publicSettings
        ]);
    }

    public function cache(): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        // Vider tout le cache des paramètres
        $settings = SystemSetting::all();
        $clearedCount = 0;

        foreach ($settings as $setting) {
            if (Cache::forget("setting.{$setting->setting_key}")) {
                $clearedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cache des paramètres vidé',
            'cleared_settings' => $clearedCount
        ]);
    }

    private function validateSettingValue($value, $type): bool
    {
        return match($type) {
            'boolean' => is_bool($value) || in_array($value, ['true', 'false', '1', '0', 1, 0]),
            'number' => is_numeric($value),
            'json' => is_array($value) || (is_string($value) && json_decode($value) !== null),
            'string' => is_string($value) || is_numeric($value),
            default => true,
        };
    }

    private function processSettingValue($value, $type): string
    {
        return match($type) {
            'boolean' => in_array($value, [true, 'true', '1', 1]) ? '1' : '0',
            'number' => (string) $value,
            'json' => is_array($value) ? json_encode($value) : $value,
            'string' => (string) $value,
            default => (string) $value,
        };
    }

    public function reset(Request $request): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('settings_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $validated = $request->validate([
            'keys' => 'required|array',
            'keys.*' => 'string',
        ]);

        $defaultSettings = $this->getDefaultSettings();
        $resetCount = 0;

        foreach ($validated['keys'] as $key) {
            if (isset($defaultSettings[$key])) {
                $default = $defaultSettings[$key];
                SystemSetting::updateOrCreate(
                    ['setting_key' => $key],
                    [
                        'setting_value' => $this->processSettingValue($default['value'], $default['type']),
                        'setting_type' => $default['type'],
                        'category' => $default['category'],
                        'description' => $default['description'],
                    ]
                );

                Cache::forget("setting.{$key}");
                $resetCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$resetCount} paramètre(s) réinitialisé(s) aux valeurs par défaut"
        ]);
    }

    private function castValue($value, $type)
    {
        return match($type) {
            'boolean' => (bool) $value,
            'number' => is_numeric($value) ? (is_float($value) ? (float) $value : (int) $value) : $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    private function getDefaultSettings(): array
    {
        return [
            // Paramètres généraux
            'app_name' => [
                'value' => 'Hajj Management System',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Nom de l\'application',
            ],
            'app_timezone' => [
                'value' => 'Africa/Dakar',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Fuseau horaire de l\'application',
            ],
            'default_language' => [
                'value' => 'fr',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Langue par défaut',
            ],

            // Paramètres entreprise
            'company_name' => [
                'value' => 'Agence Hajj & Omra',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Nom de l\'entreprise',
            ],
            'company_address' => [
                'value' => '',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Adresse de l\'entreprise',
            ],
            'company_phone' => [
                'value' => '',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Téléphone de l\'entreprise',
            ],
            'company_email' => [
                'value' => '',
                'type' => 'string',
                'category' => 'company',
                'description' => 'Email de l\'entreprise',
            ],

            // Paramètres paiements
            'default_currency' => [
                'value' => 'EUR',
                'type' => 'string',
                'category' => 'payment',
                'description' => 'Devise par défaut',
            ],
            'payment_methods' => [
                'value' => ['cash', 'bank_transfer', 'check', 'card'],
                'type' => 'json',
                'category' => 'payment',
                'description' => 'Méthodes de paiement disponibles',
            ],
        ];
    }
}