<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🕌 Création des campagnes de pèlerinage...');

        $campaigns = [
            [
                'name' => 'Hajj 2026',
                'type' => 'hajj',
                'year_hijri' => 1447,
                'year_gregorian' => 2026,
                'departure_date' => '2026-05-17',
                'return_date' => '2026-06-03',
                'price_classic' => 3400000.00,
                'price_vip' => 4200000.00,
                'description' => 'Pèlerinage du Hajj 2026 à La Mecque avec séjour complet de 17 jours.',
                'classic_description' => 'Formule Classic : Hébergement en chambre triple/quadruple, pension complète, vol direct, transport local, encadrement spirituel.',
                'vip_description' => 'Formule VIP : Hébergement en chambre double proche Haram, pension complète premium, vol direct classe affaires, transport privé, encadrement personnalisé.',
                'status' => 'active',
            ],
            [
                'name' => 'Omra Octobre 2025',
                'type' => 'omra',
                'year_hijri' => 1447,
                'year_gregorian' => 2025,
                'departure_date' => '2025-10-15',
                'return_date' => '2025-10-29',
                'price_classic' => 1200000.00,
                'price_vip' => 1650000.00,
                'description' => 'Omra d\'octobre 2025 - 14 jours à La Mecque et Médine.',
                'classic_description' => 'Formule Classic : Hébergement standard, demi-pension, vol avec escale, transport en groupe.',
                'vip_description' => 'Formule VIP : Hébergement face au Haram, pension complète, vol direct, transport privé.',
                'status' => 'active',
            ],
            [
                'name' => 'Omra Ramadan 2026',
                'type' => 'omra',
                'year_hijri' => 1447,
                'year_gregorian' => 2026,
                'departure_date' => '2026-03-15',
                'return_date' => '2026-03-29',
                'price_classic' => 1800000.00,
                'price_vip' => 2400000.00,
                'description' => 'Omra du Ramadan 2026 - 14 jours pendant le mois sacré.',
                'classic_description' => 'Formule Classic : Hébergement standard, iftar et suhur inclus, vol avec escale, prières Tarawih en groupe.',
                'vip_description' => 'Formule VIP : Hébergement premium près du Haram, repas gastronomiques, vol direct, accès privilégié aux prières.',
                'status' => 'active',
            ],
        ];

        foreach ($campaigns as $campaignData) {
            Campaign::create($campaignData);
            $this->command->info("✅ Campagne créée: {$campaignData['name']}");
        }

        $this->command->info('✅ 3 campagnes de pèlerinage créées avec succès!');
    }
}