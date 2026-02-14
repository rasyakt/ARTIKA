<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DEVELOPMENT SEEDER
 * 
 * Seeds the database with system data + demo/sample data for development.
 * DO NOT run this in production — use RoleAndSystemSeeder instead.
 * 
 * Usage:
 *   php artisan db:seed          (runs this by default)
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ============================================
        // 1. SYSTEM DATA (roles, payment methods, superadmin)
        // ============================================
        // This seeder is critical for the application to function.
        $this->call(RoleAndSystemSeeder::class);

        // ============================================
        // 2. DEMO DATA (FOR DEVELOPMENT ONLY)
        // ============================================
        // To run demo data, use: php artisan db:seed --class=DemoDataSeeder
        if (app()->environment('local')) {
            $this->command->info('💡 Tip: Run "php artisan db:seed --class=DemoDataSeeder" to populate sample data.');
        }
    }
}
