<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Pilgrim;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PilgrimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🕌 Création des pèlerins...');

        $campaigns = Campaign::all();
        $clients = Client::all()->shuffle();
        $clientIndex = 0;
        $totalPilgrims = 0;

        // Définir les objectifs fixes pour chaque campagne
        $campaignTargets = [
            'Hajj 2026' => 120,
            'Omra Octobre 2025' => 70,
            'Omra Ramadan 2026' => 80
        ];

        foreach ($campaigns as $campaign) {
            $targetPilgrims = $campaignTargets[$campaign->name];
            $this->command->info("📋 Campagne: {$campaign->name} - Objectif: {$targetPilgrims} pèlerins");

            for ($i = 0; $i < $targetPilgrims; $i++) {
                if ($clientIndex >= $clients->count()) {
                    $this->command->error('Pas assez de clients disponibles!');
                    break;
                }

                $client = $clients[$clientIndex++];

                // Déterminer la catégorie et le prix
                $category = (rand(1, 10) <= 7) ? 'classic' : 'vip'; // 70% classic, 30% vip
                $price = ($category === 'classic') ? $campaign->price_classic : $campaign->price_vip;

                // Créer le pèlerin
                Pilgrim::create([
                    'client_id' => $client->id,
                    'campaign_id' => $campaign->id,
                    'firstname' => $client->firstname,
                    'lastname' => $client->lastname,
                    'date_of_birth' => $client->date_of_birth,
                    'phone' => $client->phone,
                    'email' => $client->email,
                    'gender' => $client->gender,
                    'category' => $category,
                    'status' => 'registered',
                ]);

                $totalPilgrims++;
            }

            $this->command->info("✅ {$targetPilgrims} pèlerins créés pour {$campaign->name}");
        }

        $this->command->info("✅ Total: {$totalPilgrims} pèlerins créés avec succès!");
    }
}