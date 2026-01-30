<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $adminRole = \App\Models\Role::create(['name' => 'admin', 'description' => 'Administrator']);
        $cashierRole = \App\Models\Role::create(['name' => 'cashier', 'description' => 'Kasir']);
        $warehouseRole = \App\Models\Role::create(['name' => 'warehouse', 'description' => 'Staff Gudang']);
        $kepalaTokoRole = \App\Models\Role::create(['name' => 'kepala_toko', 'description' => 'Kepala Toko']);

        // Users
        \App\Models\User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('password'), // password
            'role_id' => $adminRole->id,
        ]);

        \App\Models\User::create([
            'name' => 'Kasir 01',
            'username' => 'kasir1',
            'nis' => '12345',
            'password' => bcrypt('password'),
            'role_id' => $cashierRole->id,
        ]);

        \App\Models\User::create([
            'name' => 'Staff Gudang',
            'username' => 'gudang',
            'password' => bcrypt('password'),
            'role_id' => $warehouseRole->id,
        ]);

        \App\Models\User::create([
            'name' => 'Kepala Toko',
            'username' => 'kepalatoko',
            'password' => bcrypt('password'),
            'role_id' => $kepalaTokoRole->id,
        ]);
        // Categories
        $catSnack = \App\Models\Category::create(['name' => 'Snack', 'slug' => 'snack']);
        $catDrink = \App\Models\Category::create(['name' => 'Minuman', 'slug' => 'drink']);
        $catFood = \App\Models\Category::create(['name' => 'Makanan', 'slug' => 'food']);
        $catDairy = \App\Models\Category::create(['name' => 'Dairy', 'slug' => 'dairy']);
        $catHousehold = \App\Models\Category::create(['name' => 'Peralatan', 'slug' => 'household']);

        // Products - Snacks
        $products = [
            ['barcode' => '899999911111', 'name' => 'Chitato Lite', 'category_id' => $catSnack->id, 'price' => 15000, 'cost_price' => 12000],
            ['barcode' => '899999911112', 'name' => 'Lays Original', 'category_id' => $catSnack->id, 'price' => 18000, 'cost_price' => 14000],
            ['barcode' => '899999911113', 'name' => 'Pringles', 'category_id' => $catSnack->id, 'price' => 25000, 'cost_price' => 20000],
            ['barcode' => '899999911114', 'name' => 'Oreo', 'category_id' => $catSnack->id, 'price' => 12000, 'cost_price' => 9000],
            ['barcode' => '899999911115', 'name' => 'Biskuat', 'category_id' => $catSnack->id, 'price' => 8000, 'cost_price' => 6000],

            // Drinks
            ['barcode' => '899999922222', 'name' => 'Teh Botol Sosro', 'category_id' => $catDrink->id, 'price' => 5000, 'cost_price' => 3000],
            ['barcode' => '899999922223', 'name' => 'Aqua 600ml', 'category_id' => $catDrink->id, 'price' => 4000, 'cost_price' => 2500],
            ['barcode' => '899999922224', 'name' => 'Coca Cola', 'category_id' => $catDrink->id, 'price' => 7000, 'cost_price' => 5000],
            ['barcode' => '899999922225', 'name' => 'Fanta', 'category_id' => $catDrink->id, 'price' => 7000, 'cost_price' => 5000],
            ['barcode' => '899999922226', 'name' => 'Sprite', 'category_id' => $catDrink->id, 'price' => 7000, 'cost_price' => 5000],

            // Food
            ['barcode' => '899999933333', 'name' => 'Indomie Goreng', 'category_id' => $catFood->id, 'price' => 3500, 'cost_price' => 2500],
            ['barcode' => '899999933334', 'name' => 'Indomie Soto', 'category_id' => $catFood->id, 'price' => 3500, 'cost_price' => 2500],
            ['barcode' => '899999933335', 'name' => 'Mie Sedaap', 'category_id' => $catFood->id, 'price' => 3000, 'cost_price' => 2200],
            ['barcode' => '899999933336', 'name' => 'Pop Mie', 'category_id' => $catFood->id, 'price' => 6000, 'cost_price' => 4500],

            // Dairy
            ['barcode' => '899999944444', 'name' => 'Susu Ultra Milk', 'category_id' => $catDairy->id, 'price' => 12000, 'cost_price' => 9000],
            ['barcode' => '899999944445', 'name' => 'Yakult', 'category_id' => $catDairy->id, 'price' => 10000, 'cost_price' => 7500],
            ['barcode' => '899999944446', 'name' => 'Yogurt Cimory', 'category_id' => $catDairy->id, 'price' => 8000, 'cost_price' => 6000],

            // Household
            ['barcode' => '899999955555', 'name' => 'Sabun Lifebuoy', 'category_id' => $catHousehold->id, 'price' => 5000, 'cost_price' => 3500],
            ['barcode' => '899999955556', 'name' => 'Shampo Pantene', 'category_id' => $catHousehold->id, 'price' => 18000, 'cost_price' => 14000],
            ['barcode' => '899999955557', 'name' => 'Pasta Gigi Pepsodent', 'category_id' => $catHousehold->id, 'price' => 12000, 'cost_price' => 9000],
        ];

        foreach ($products as $productData) {
            $product = \App\Models\Product::create($productData);
            // Add stock for product
            \App\Models\Stock::create([
                'product_id' => $product->id,
                'quantity' => rand(50, 200)
            ]);
        }

        // Sample Suppliers (replacing Customers seeds)
        \App\Models\Supplier::create([
            'name' => 'CV. Sumber Jaya',
            'phone' => '081298765432',
            'email' => 'sumberjaya@example.com',
            'address' => 'Jl. Supplier No. 10',
            'last_purchase_at' => now()->subDays(10),
        ]);

        \App\Models\Supplier::create([
            'name' => 'UD. Maju Sentosa',
            'phone' => '081233344455',
            'email' => 'maju@example.com',
            'address' => 'Jl. Supplier No. 20',
            'last_purchase_at' => now()->subMonths(1),
        ]);

        \App\Models\Supplier::create([
            'name' => 'PT. Grosir Utama',
            'phone' => '081277788899',
            'email' => 'grosir@example.com',
            'address' => 'Jl. Supplier No. 30',
            'last_purchase_at' => now()->subMonths(2),
        ]);

        // Payment Methods
        \App\Models\PaymentMethod::create(['name' => 'Cash', 'slug' => 'cash']);
        \App\Models\PaymentMethod::create(['name' => 'QRIS', 'slug' => 'qris']);
        \App\Models\PaymentMethod::create(['name' => 'Debit Card', 'slug' => 'debit']);
        \App\Models\PaymentMethod::create(['name' => 'Credit Card', 'slug' => 'credit']);
        \App\Models\PaymentMethod::create(['name' => 'E-Wallet', 'slug' => 'ewallet']);
    }
}
