<?php
// Script rapide pour créer un utilisateur test
// À exécuter via: php create-test-user.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Vérifier si l'utilisateur existe déjà
    $existingUser = User::where('email', 'test@hajj.com')->first();

    if ($existingUser) {
        echo "✅ L'utilisateur test@hajj.com existe déjà.\n";
        echo "🔑 Mot de passe: password\n";
        echo "🌐 Connectez-vous sur: http://127.0.0.1:8000/login\n";
    } else {
        // Créer un nouvel utilisateur
        $user = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@hajj.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        echo "✅ Utilisateur test créé avec succès!\n";
        echo "📧 Email: test@hajj.com\n";
        echo "🔑 Mot de passe: password\n";
        echo "👑 Admin: Oui\n";
        echo "🌐 Connectez-vous sur: http://127.0.0.1:8000/login\n";
    }

    // Afficher les statistiques des utilisateurs
    $totalUsers = User::count();
    $adminUsers = User::where('is_admin', true)->count();

    echo "\n📊 Statistiques:\n";
    echo "👥 Total utilisateurs: {$totalUsers}\n";
    echo "👑 Administrateurs: {$adminUsers}\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "💡 Assurez-vous que la base de données est configurée et accessible.\n";
}
?>