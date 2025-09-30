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
        $this->command->info('👤 Création des utilisateurs admin...');

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

        // Create additional admin users
        $additionalAdmins = [
            [
                'name' => 'Admin Test 1',
                'email' => 'admin1@hajj.com',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Test 2',
                'email' => 'admin2@hajj.com',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Test 3',
                'email' => 'admin3@hajj.com',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Test 4',
                'email' => 'admin4@hajj.com',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Test 5',
                'email' => 'admin5@hajj.com',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($additionalAdmins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }

        $this->command->info('✅ Utilisateurs admin créés avec succès!');
        $this->command->info('📧 Admin principal: ismailahamadou5@gmail.com (mot de passe: 12345678)');
        $this->command->info('📧 Admins test: admin1@hajj.com à admin5@hajj.com (mot de passe: admin123)');
        $this->command->info('👑 Rôle: Administrateurs');
    }
}