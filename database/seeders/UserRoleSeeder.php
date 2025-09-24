<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrateur',
                'description' => 'Accès complet au système',
                'permissions' => json_encode(['all']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrateur',
                'description' => 'Gestion des utilisateurs et des campagnes',
                'permissions' => json_encode(['manage_users', 'manage_campaigns', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator',
                'display_name' => 'Opérateur',
                'description' => 'Gestion des pèlerins et documents',
                'permissions' => json_encode(['manage_pilgrims', 'manage_documents']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Comptable',
                'description' => 'Gestion des paiements et comptabilité',
                'permissions' => json_encode(['manage_payments', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Caissier',
                'description' => 'Encaissement et réception des paiements',
                'permissions' => json_encode(['receive_payments']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'agent',
                'display_name' => 'Agent',
                'description' => 'Consultation des données',
                'permissions' => json_encode(['view_pilgrims', 'view_payments']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('user_roles')->insert($roles);
    }
}
