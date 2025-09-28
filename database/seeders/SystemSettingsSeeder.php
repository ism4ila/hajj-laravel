<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Configuration des paramètres système...');

        $settings = [
            // Informations de l'agence
            [
                'setting_key' => 'company_name',
                'setting_value' => 'Agence Hajj & Omra Excellence',
                'category' => 'company',
                'description' => 'Nom de l\'agence'
            ],
            [
                'setting_key' => 'company_slogan',
                'setting_value' => 'Votre partenaire de confiance pour un pèlerinage serein',
                'category' => 'company',
                'description' => 'Slogan de l\'agence'
            ],
            [
                'setting_key' => 'company_address',
                'setting_value' => 'Avenue de l\'Indépendance, Bonanjo',
                'category' => 'company',
                'description' => 'Adresse de l\'agence'
            ],
            [
                'setting_key' => 'company_city',
                'setting_value' => 'Douala',
                'category' => 'company',
                'description' => 'Ville de l\'agence'
            ],
            [
                'setting_key' => 'company_country',
                'setting_value' => 'Cameroun',
                'category' => 'company',
                'description' => 'Pays de l\'agence'
            ],
            [
                'setting_key' => 'company_phone',
                'setting_value' => '+237 690 123 456',
                'category' => 'company',
                'description' => 'Téléphone principal'
            ],
            [
                'setting_key' => 'company_phone2',
                'setting_value' => '+237 677 654 321',
                'category' => 'company',
                'description' => 'Téléphone secondaire'
            ],
            [
                'setting_key' => 'company_whatsapp',
                'setting_value' => '+237 690 123 456',
                'category' => 'company',
                'description' => 'Numéro WhatsApp'
            ],
            [
                'setting_key' => 'company_email',
                'setting_value' => 'contact@hajjomra-excellence.cm',
                'category' => 'company',
                'description' => 'Email de contact'
            ],
            [
                'setting_key' => 'company_website',
                'setting_value' => 'www.hajjomra-excellence.cm',
                'category' => 'company',
                'description' => 'Site web'
            ],
            [
                'setting_key' => 'company_logo',
                'setting_value' => 'logo.png',
                'category' => 'company',
                'description' => 'Logo de l\'agence'
            ],
            [
                'setting_key' => 'company_registration',
                'setting_value' => 'RC/DLA/2024/A/789456',
                'category' => 'company',
                'description' => 'Numéro d\'enregistrement'
            ],
            [
                'setting_key' => 'company_license',
                'setting_value' => 'LIC-HAJJ-CM-2024-001',
                'category' => 'company',
                'description' => 'Licence de pèlerinage'
            ],

            // Paramètres généraux
            [
                'setting_key' => 'default_currency',
                'setting_value' => 'FCFA',
                'category' => 'general',
                'description' => 'Devise par défaut'
            ],
            [
                'setting_key' => 'currency_symbol',
                'setting_value' => 'FCFA',
                'category' => 'general',
                'description' => 'Symbole de la devise'
            ],
            [
                'setting_key' => 'timezone',
                'setting_value' => 'Africa/Douala',
                'category' => 'general',
                'description' => 'Fuseau horaire'
            ],
            [
                'setting_key' => 'language',
                'setting_value' => 'fr',
                'category' => 'general',
                'description' => 'Langue par défaut'
            ],

            // Paramètres de paiement
            [
                'setting_key' => 'payment_terms',
                'setting_value' => 'Paiement par tranche autorisé pour le Hajj. Paiement intégral requis pour la Omra.',
                'category' => 'payment',
                'description' => 'Conditions de paiement'
            ],
            [
                'setting_key' => 'late_payment_fee',
                'setting_value' => '50000',
                'category' => 'payment',
                'description' => 'Frais de retard (FCFA)'
            ],

            // Informations légales
            [
                'setting_key' => 'legal_notice',
                'setting_value' => 'Agence agréée par le Ministère des Affaires Religieuses du Cameroun pour l\'organisation des pèlerinages.',
                'category' => 'legal',
                'description' => 'Mention légale'
            ],
            [
                'setting_key' => 'terms_conditions',
                'setting_value' => 'Voir nos conditions générales de vente disponibles sur notre site web.',
                'category' => 'legal',
                'description' => 'Conditions générales'
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
            $this->command->info("✅ Setting créé/mis à jour: {$setting['setting_key']}");
        }

        $this->command->info('✅ Tous les paramètres système ont été configurés!');
    }
}