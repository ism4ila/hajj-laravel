<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the super_admin role
        $superAdminRole = UserRole::where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run UserRoleSeeder first.');
            return;
        }

        // Create the main admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'ismailahamadou5@gmail.com'],
            [
                'name' => 'Ismael Hamadou',
                'email' => 'ismailahamadou5@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => $superAdminRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: ismailahamadou5@gmail.com');
        $this->command->info('Password: 12345678');
        $this->command->info('Role: Super Administrator');

        // Create additional sample users for different roles
        $roles = UserRole::whereIn('name', ['admin', 'operator', 'agent', 'accountant'])->get()->keyBy('name');

        $sampleUsers = [
            [
                'name' => 'Admin Système',
                'email' => 'admin@hajj-agency.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Opérateur Principal',
                'email' => 'operator@hajj-agency.com',
                'role' => 'operator',
            ],
            [
                'name' => 'Agent Commercial',
                'email' => 'agent@hajj-agency.com',
                'role' => 'agent',
            ],
            [
                'name' => 'Comptable',
                'email' => 'accountant@hajj-agency.com',
                'role' => 'accountant',
            ],
        ];

        foreach ($sampleUsers as $userData) {
            if (isset($roles[$userData['role']])) {
                User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make('password123'),
                        'role_id' => $roles[$userData['role']]->id,
                        'email_verified_at' => now(),
                    ]
                );

                $this->command->info("Created user: {$userData['name']} ({$userData['email']}) - Role: {$userData['role']}");
            }
        }

        $this->command->info('All users created successfully!');
    }
}