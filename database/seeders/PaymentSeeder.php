<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Pilgrim;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Génération des paiements aléatoires...');

        $payment_methods = ['cash', 'bank_transfer', 'check'];
        $payment_count = 0;

        // Récupérer l'admin pour les paiements
        $admin = User::where('email', 'ismailahamadou5@gmail.com')->first();
        if (!$admin) {
            $this->command->error('Utilisateur admin non trouvé!');
            return;
        }

        $pilgrims = Pilgrim::with('campaign')->get();

        foreach ($pilgrims as $pilgrim) {
            // Calculer le montant total basé sur la campagne et la catégorie
            $total_amount = ($pilgrim->category === 'classic') ? $pilgrim->campaign->price_classic : $pilgrim->campaign->price_vip;
            $campaign_type = $pilgrim->campaign->type;

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
                    'created_by' => $admin->id,
                    'created_at' => $payment_date,
                    'updated_at' => $payment_date,
                ]);

                $this->command->info("✅ Paiement Omra créé pour {$pilgrim->firstname} {$pilgrim->lastname}");

            } else {
                // Hajj: 1, 2 ou 3 tranches
                $tranches = rand(1, 3);
                $remaining_amount = $total_amount;

                $this->command->info("📊 Hajj - {$pilgrim->firstname} {$pilgrim->lastname}: {$tranches} tranche(s)");

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
                        'created_by' => $admin->id,
                        'created_at' => $payment_date,
                        'updated_at' => $payment_date,
                    ]);
                }

                $this->command->info("✅ {$tranches} paiements Hajj créés pour {$pilgrim->firstname} {$pilgrim->lastname}");
            }
        }

        $this->command->info("✅ {$payment_count} paiements générés au total!");

        // Statistiques finales
        $this->command->info('📊 STATISTIQUES DES PAIEMENTS:');
        $total_payments = Payment::sum('amount');
        $this->command->info("💰 Total général: " . number_format($total_payments, 0, ',', ' ') . " FCFA");

        $payment_by_method = Payment::selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        foreach ($payment_by_method as $method) {
            $this->command->info("- {$method->payment_method}: " . number_format($method->total, 0, ',', ' ') . " FCFA");
        }
    }
}