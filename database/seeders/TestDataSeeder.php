<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Pilgrim;
use App\Models\Payment;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Génération des données de test pour le système Hajj...');

        // Vider les tables dans le bon ordre avec les contraintes
        $this->command->info('📊 Vidage des tables...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Payment::truncate();
        Pilgrim::truncate();
        Client::truncate();
        Campaign::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Créer les campagnes
        $this->command->info('🕌 Création des campagnes...');
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
        }

        $this->command->info('✅ 3 campagnes créées');

        // 2. Noms musulmans camerounais
        $prenoms_hommes = [
            'Ahmadou', 'Ibrahim', 'Moussa', 'Abdoulaye', 'Alhadji', 'Bouba', 'Hamidou', 'Issa', 'Mahamat', 'Omar',
            'Saliou', 'Youssouf', 'Amadou', 'Oumarou', 'Aboubakar', 'Mohamadou', 'Alioum', 'Bakari', 'Harouna', 'Mallam',
            'Souley', 'Tchiroma', 'Aboubakary', 'Adamou', 'Alamine', 'Bello', 'Djafarou', 'Garba', 'Idi', 'Jibril'
        ];

        $prenoms_femmes = [
            'Aïcha', 'Fatima', 'Khadija', 'Mariam', 'Aminata', 'Hadjia', 'Roukayya', 'Zainab', 'Hadja', 'Maimou',
            'Safiya', 'Halima', 'Asma', 'Djamila', 'Fadimatou', 'Habiba', 'Hawa', 'Maimouna', 'Ramatou', 'Salamatou',
            'Djamilatou', 'Haouaria', 'Mariama', 'Nafissa', 'Rahmatou', 'Sakinah', 'Wassila', 'Yasmina', 'Zeynabou', 'Balkissa'
        ];

        $noms_famille = [
            'Abba', 'Adoum', 'Baba', 'Béchir', 'Boukar', 'Daoud', 'Farouk', 'Garga', 'Hassan', 'Issa',
            'Mahamat', 'Mallam', 'Moussa', 'Omar', 'Ousman', 'Saliou', 'Tchiroma', 'Yaya', 'Zarma', 'Abakar',
            'Babagana', 'Brahim', 'Djamal', 'Haroun', 'Idriss', 'Kiari', 'Lawan', 'Modibo', 'Njoya', 'Ousmane'
        ];

        $villes_cameroun = [
            'Douala', 'Yaoundé', 'Garoua', 'Maroua', 'Bamenda', 'Bafoussam', 'Ngaoundéré', 'Bertoua', 'Edéa', 'Kribi'
        ];

        // 3. Générer les clients et pèlerins
        $this->command->info('👥 Génération des clients et pèlerins...');

        $all_pilgrims = [];
        $totalPilgrims = 0;

        foreach (Campaign::all() as $campaign) {
            $target_pilgrims = ($campaign->type === 'hajj') ? 120 : rand(50, 90);
            $this->command->info("📋 Campagne: {$campaign->name} - Objectif: {$target_pilgrims} pèlerins");

            for ($i = 0; $i < $target_pilgrims; $i++) {
                $is_male = rand(0, 1);
                $prenom = $is_male ? $prenoms_hommes[array_rand($prenoms_hommes)] : $prenoms_femmes[array_rand($prenoms_femmes)];
                $nom = $noms_famille[array_rand($noms_famille)];
                $ville = $villes_cameroun[array_rand($villes_cameroun)];

                // Créer le client
                $client = Client::create([
                    'firstname' => $prenom,
                    'lastname' => $nom,
                    'phone' => '+237 6' . rand(50000000, 99999999),
                    'email' => strtolower($prenom . '.' . $nom . rand(1, 999) . '@email.cm'),
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 65))->format('Y-m-d'),
                    'gender' => $is_male ? 'male' : 'female',
                    'nationality' => 'Cameroun',
                    'region' => $ville,
                    'address' => 'Quartier ' . rand(1, 10) . ', ' . $ville,
                    'is_active' => 1,
                ]);

                // Déterminer la catégorie et le prix
                $category = (rand(1, 10) <= 7) ? 'classic' : 'vip'; // 70% classic, 30% vip
                $price = ($category === 'classic') ? $campaign->price_classic : $campaign->price_vip;

                // Créer le pèlerin
                $pilgrim = Pilgrim::create([
                    'client_id' => $client->id,
                    'campaign_id' => $campaign->id,
                    'firstname' => $prenom,
                    'lastname' => $nom,
                    'date_of_birth' => $client->date_of_birth,
                    'phone' => $client->phone,
                    'email' => $client->email,
                    'gender' => $is_male ? 'male' : 'female',
                    'category' => $category,
                    'total_amount' => $price,
                    'status' => 'registered',
                ]);

                $all_pilgrims[] = [
                    'pilgrim' => $pilgrim,
                    'campaign_type' => $campaign->type,
                    'total_amount' => $price
                ];
                $totalPilgrims++;
            }
        }

        $this->command->info("✅ {$totalPilgrims} pèlerins créés");

        // 4. Générer les paiements
        $this->command->info('💰 Génération des paiements...');

        $payment_methods = ['cash', 'bank_transfer', 'check', 'card'];
        $payment_count = 0;

        foreach ($all_pilgrims as $pilgrimData) {
            $pilgrim = $pilgrimData['pilgrim'];
            $total_amount = $pilgrimData['total_amount'];
            $campaign_type = $pilgrimData['campaign_type'];

            if ($campaign_type === 'omra') {
                // Omra: toujours un seul paiement
                $amount = $total_amount;
                $payment_date = Carbon::now()->subDays(rand(1, 30));

                Payment::create([
                    'pilgrim_id' => $pilgrim->id,
                    'amount' => $amount,
                    'payment_method' => $payment_methods[array_rand($payment_methods)],
                    'payment_date' => $payment_date->format('Y-m-d'),
                    'reference' => 'PAY-' . date('Y') . '-' . str_pad(++$payment_count, 6, '0', STR_PAD_LEFT),
                    'status' => 'completed',
                    'notes' => 'Paiement intégral Omra',
                    'created_at' => $payment_date,
                    'updated_at' => $payment_date,
                ]);
            } else {
                // Hajj: 1, 2 ou 3 tranches
                $tranches = rand(1, 3);
                $remaining_amount = $total_amount;

                for ($t = 1; $t <= $tranches; $t++) {
                    if ($t === $tranches) {
                        // Dernière tranche = le restant
                        $amount = $remaining_amount;
                    } else {
                        // Tranche intermédiaire
                        $percentage = rand(20, 50); // 20% à 50% du montant total
                        $amount = ($total_amount * $percentage) / 100;
                        $amount = round($amount / 10000) * 10000; // Arrondir aux 10k
                        $remaining_amount -= $amount;
                    }

                    $payment_date = Carbon::now()->subDays(rand(1, 90));

                    Payment::create([
                        'pilgrim_id' => $pilgrim->id,
                        'amount' => $amount,
                        'payment_method' => $payment_methods[array_rand($payment_methods)],
                        'payment_date' => $payment_date->format('Y-m-d'),
                        'reference' => 'PAY-' . date('Y') . '-' . str_pad(++$payment_count, 6, '0', STR_PAD_LEFT),
                        'status' => 'completed',
                        'notes' => "Paiement tranche {$t}/{$tranches} - Hajj",
                        'created_at' => $payment_date,
                        'updated_at' => $payment_date,
                    ]);
                }
            }
        }

        $this->command->info("✅ {$payment_count} paiements générés");

        // Statistiques finales
        $this->command->info('📊 STATISTIQUES FINALES:');
        $this->command->info('========================');

        $stats = [
            'Campagnes' => Campaign::count(),
            'Clients' => Client::count(),
            'Pèlerins' => Pilgrim::count(),
            'Paiements' => Payment::count(),
        ];

        foreach ($stats as $type => $count) {
            $this->command->info("- {$type}: {$count}");
        }

        // Détail par campagne
        foreach (Campaign::with('pilgrims')->get() as $campaign) {
            $this->command->info("🕌 {$campaign->name} ({$campaign->type}): {$campaign->pilgrims->count()} pèlerins");
        }

        $total_payments = Payment::sum('amount');
        $this->command->info("💰 Total général: " . number_format($total_payments, 0, ',', ' ') . " FCFA");

        $this->command->info('🎉 Génération terminée avec succès !');
    }
}
