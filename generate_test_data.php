<?php

require_once 'vendor/autoload.php';

// Configuration de la connexion à la base de données
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'hajj_management',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🚀 Génération des données de test pour le système Hajj...\n\n";

// Vider les tables dans le bon ordre
echo "📊 Vidage des tables...\n";
Capsule::statement('SET FOREIGN_KEY_CHECKS = 0');
Capsule::table('payments')->delete();
Capsule::table('pilgrims')->delete();
Capsule::table('clients')->delete();
Capsule::table('campaigns')->delete();
Capsule::statement('SET FOREIGN_KEY_CHECKS = 1');

// 1. Créer les campagnes
echo "🕌 Création des campagnes...\n";
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
        'created_at' => now(),
        'updated_at' => now(),
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
        'created_at' => now(),
        'updated_at' => now(),
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
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

foreach ($campaigns as $campaign) {
    Capsule::table('campaigns')->insert($campaign);
}

echo "✅ 3 campagnes créées\n\n";

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
echo "👥 Génération des clients et pèlerins...\n";

$campaign_ids = Capsule::table('campaigns')->pluck('id')->toArray();
$all_pilgrims = [];

foreach ($campaign_ids as $campaign_id) {
    $campaign = Capsule::table('campaigns')->where('id', $campaign_id)->first();
    $target_pilgrims = ($campaign->type === 'hajj') ? 120 : rand(50, 90);

    echo "📋 Campagne: {$campaign->name} - Objectif: {$target_pilgrims} pèlerins\n";

    for ($i = 0; $i < $target_pilgrims; $i++) {
        $is_male = rand(0, 1);
        $prenom = $is_male ? $prenoms_hommes[array_rand($prenoms_hommes)] : $prenoms_femmes[array_rand($prenoms_femmes)];
        $nom = $noms_famille[array_rand($noms_famille)];
        $ville = $villes_cameroun[array_rand($villes_cameroun)];

        // Créer le client
        $client_id = Capsule::table('clients')->insertGetId([
            'firstname' => $prenom,
            'lastname' => $nom,
            'phone' => '+237 6' . rand(50000000, 99999999),
            'email' => strtolower($prenom . '.' . $nom . '@email.cm'),
            'date_of_birth' => date('Y-m-d', strtotime('-' . rand(25, 65) . ' years')),
            'gender' => $is_male ? 'male' : 'female',
            'nationality' => 'Cameroun',
            'region' => $ville,
            'address' => 'Quartier ' . rand(1, 10) . ', ' . $ville,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Déterminer la catégorie et le prix
        $category = (rand(1, 10) <= 7) ? 'classic' : 'vip'; // 70% classic, 30% vip
        $price = ($category === 'classic') ? $campaign->price_classic : $campaign->price_vip;

        // Créer le pèlerin
        $pilgrim_id = Capsule::table('pilgrims')->insertGetId([
            'client_id' => $client_id,
            'campaign_id' => $campaign_id,
            'firstname' => $prenom,
            'lastname' => $nom,
            'date_of_birth' => Capsule::table('clients')->where('id', $client_id)->value('date_of_birth'),
            'phone' => Capsule::table('clients')->where('id', $client_id)->value('phone'),
            'email' => Capsule::table('clients')->where('id', $client_id)->value('email'),
            'gender' => $is_male ? 'male' : 'female',
            'category' => $category,
            'total_amount' => $price,
            'status' => 'registered',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $all_pilgrims[] = [
            'id' => $pilgrim_id,
            'campaign_type' => $campaign->type,
            'total_amount' => $price
        ];
    }
}

echo "✅ " . count($all_pilgrims) . " pèlerins créés\n\n";

// 4. Générer les paiements
echo "💰 Génération des paiements...\n";

$payment_methods = ['cash', 'bank_transfer', 'check', 'card'];
$payment_count = 0;

foreach ($all_pilgrims as $pilgrim) {
    $total_amount = $pilgrim['total_amount'];
    $campaign_type = $pilgrim['campaign_type'];

    if ($campaign_type === 'omra') {
        // Omra: toujours un seul paiement
        $amount = $total_amount;
        $payment_date = date('Y-m-d', strtotime('-' . rand(1, 30) . ' days'));

        Capsule::table('payments')->insert([
            'pilgrim_id' => $pilgrim['id'],
            'amount' => $amount,
            'payment_method' => $payment_methods[array_rand($payment_methods)],
            'payment_date' => $payment_date,
            'reference' => 'PAY-' . date('Y') . '-' . str_pad(++$payment_count, 6, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'notes' => 'Paiement intégral Omra',
            'created_at' => $payment_date . ' ' . rand(8, 18) . ':' . rand(10, 59) . ':00',
            'updated_at' => $payment_date . ' ' . rand(8, 18) . ':' . rand(10, 59) . ':00',
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

            $payment_date = date('Y-m-d', strtotime('-' . rand(1, 90) . ' days'));

            Capsule::table('payments')->insert([
                'pilgrim_id' => $pilgrim['id'],
                'amount' => $amount,
                'payment_method' => $payment_methods[array_rand($payment_methods)],
                'payment_date' => $payment_date,
                'reference' => 'PAY-' . date('Y') . '-' . str_pad(++$payment_count, 6, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'notes' => "Paiement tranche {$t}/{$tranches} - Hajj",
                'created_at' => $payment_date . ' ' . rand(8, 18) . ':' . rand(10, 59) . ':00',
                'updated_at' => $payment_date . ' ' . rand(8, 18) . ':' . rand(10, 59) . ':00',
            ]);
        }
    }
}

echo "✅ {$payment_count} paiements générés\n\n";

// Statistiques finales
echo "📊 STATISTIQUES FINALES:\n";
echo "========================\n";

$stats = [
    'Campagnes' => Capsule::table('campaigns')->count(),
    'Clients' => Capsule::table('clients')->count(),
    'Pèlerins' => Capsule::table('pilgrims')->count(),
    'Paiements' => Capsule::table('payments')->count(),
];

foreach ($stats as $type => $count) {
    echo "- {$type}: {$count}\n";
}

echo "\n";

// Détail par campagne
$campaigns_stats = Capsule::table('campaigns')
    ->leftJoin('pilgrims', 'campaigns.id', '=', 'pilgrims.campaign_id')
    ->select('campaigns.name', 'campaigns.type', Capsule::raw('COUNT(pilgrims.id) as pilgrim_count'))
    ->groupBy('campaigns.id', 'campaigns.name', 'campaigns.type')
    ->get();

foreach ($campaigns_stats as $camp) {
    echo "🕌 {$camp->name} ({$camp->type}): {$camp->pilgrim_count} pèlerins\n";
}

echo "\n💰 Montants totaux des paiements:\n";
$total_payments = Capsule::table('payments')->sum('amount');
echo "- Total général: " . number_format($total_payments, 0, ',', ' ') . " FCFA\n";

$payment_by_campaign = Capsule::table('payments')
    ->join('pilgrims', 'payments.pilgrim_id', '=', 'pilgrims.id')
    ->join('campaigns', 'pilgrims.campaign_id', '=', 'campaigns.id')
    ->select('campaigns.name', Capsule::raw('SUM(payments.amount) as total'))
    ->groupBy('campaigns.id', 'campaigns.name')
    ->get();

foreach ($payment_by_campaign as $payment) {
    echo "- {$payment->name}: " . number_format($payment->total, 0, ',', ' ') . " FCFA\n";
}

echo "\n🎉 Génération terminée avec succès !\n";

function now() {
    return date('Y-m-d H:i:s');
}