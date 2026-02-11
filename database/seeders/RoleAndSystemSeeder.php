<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

/**
 * PRODUCTION SEEDER
 * 
 * This seeder contains system-critical data required for the application to function.
 * It is safe to run multiple times (uses firstOrCreate).
 * 
 * Usage:
 *   php artisan db:seed --class=RoleAndSystemSeeder
 */
class RoleAndSystemSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // ROLES (required for role-based access control)
        // ============================================
        $superadminRole = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['description' => 'Developer / System Administrator with full access to technical tools.']
        );

        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator']
        );

        Role::firstOrCreate(
            ['name' => 'manager'],
            ['description' => 'Kepala Toko']
        );

        Role::firstOrCreate(
            ['name' => 'cashier'],
            ['description' => 'Kasir']
        );

        Role::firstOrCreate(
            ['name' => 'warehouse'],
            ['description' => 'Staff Gudang']
        );

        // ============================================
        // INITIAL SUPERADMIN ACCOUNT
        // ============================================
        // This is the first account for initial system setup.
        // After deployment, create other users via Admin → User Management.
        // IMPORTANT: Change this password immediately after first login!
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Superadmin',
                'password' => bcrypt('superadmin123'),
                'role_id' => $superadminRole->id,
            ]
        );

        // ============================================
        // PAYMENT METHODS (required for POS transactions)
        // ============================================
        $methods = [
            ['name' => 'Cash', 'slug' => 'cash'],
            ['name' => 'QRIS', 'slug' => 'qris'],
            ['name' => 'Debit Card', 'slug' => 'debit'],
            ['name' => 'Credit Card', 'slug' => 'credit'],
            ['name' => 'E-Wallet', 'slug' => 'ewallet'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['slug' => $method['slug']],
                ['name' => $method['name']]
            );
        }

        $this->command->info('✅ System roles, superadmin account, and payment methods have been seeded.');
        $this->command->warn('⚠️  Please change the superadmin password after first login!');
    }
}
