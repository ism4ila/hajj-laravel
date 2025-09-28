<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Création de l\'utilisateur admin...');

        // Create the main admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'ismailahamadou5@gmail.com'],
            [
                'name' => 'Ismael Hamadou',
                'email' => 'ismailahamadou5@gmail.com',
                'password' => Hash::make('12345678'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Utilisateur admin créé avec succès!');
        $this->command->info('📧 Email: ismailahamadou5@gmail.com');
        $this->command->info('🔐 Mot de passe: 12345678');
        $this->command->info('👑 Rôle: Super Administrateur');
    }
}