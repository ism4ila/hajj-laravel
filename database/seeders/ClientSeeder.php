<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Génération des clients camerounais...');

        // Noms musulmans camerounais
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

        // Créer exactement le nombre de clients nécessaires
        $totalClients = 120 + 70 + 80; // Hajj + Omra Oct + Omra Ramadan
        $this->command->info("🎯 Total de clients à créer: {$totalClients}");

        // Générer les clients
        for ($i = 0; $i < $totalClients; $i++) {
            $is_male = rand(0, 1);
            $prenom = $is_male ? $prenoms_hommes[array_rand($prenoms_hommes)] : $prenoms_femmes[array_rand($prenoms_femmes)];
            $nom = $noms_famille[array_rand($noms_famille)];
            $ville = $villes_cameroun[array_rand($villes_cameroun)];

            Client::create([
                'firstname' => $prenom,
                'lastname' => $nom,
                'phone' => '+237 6' . rand(50000000, 99999999),
                'email' => strtolower($prenom . '.' . $nom . rand(1, 9999) . '@email.cm'),
                'date_of_birth' => Carbon::now()->subYears(rand(25, 65))->format('Y-m-d'),
                'gender' => $is_male ? 'male' : 'female',
                'nationality' => 'Cameroun',
                'region' => $ville,
                'address' => 'Quartier ' . rand(1, 10) . ', ' . $ville,
                'is_active' => 1,
            ]);

            if (($i + 1) % 50 == 0) {
                $this->command->info("✅ " . ($i + 1) . " clients créés...");
            }
        }

        $this->command->info("✅ " . $totalClients . " clients camerounais créés avec succès!");
    }
}