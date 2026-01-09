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

        // Branches
        $pusat = \App\Models\Branch::create(['name' => 'Pusat', 'address' => 'Jl. Utama No. 1']);
        $cabang1 = \App\Models\Branch::create(['name' => 'Cabang 1', 'address' => 'Jl. Cabang No. 2']);

        // Users
        \App\Models\User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'password' => bcrypt('password'), // password
            'role_id' => $adminRole->id,
            'branch_id' => $pusat->id,
        ]);

        \App\Models\User::create([
            'name' => 'Kasir 01',
            'username' => 'kasir1',
            'nis' => '12345',
            'password' => bcrypt('password'),
            'role_id' => $cashierRole->id,
            'branch_id' => $pusat->id,
        ]);

        \App\Models\User::create([
            'name' => 'Staff Gudang',
            'username' => 'gudang',
            'password' => bcrypt('password'),
            'role_id' => $warehouseRole->id,
            'branch_id' => $pusat->id,
        ]);
        // Categories
        $catSnack = \App\Models\Category::create(['name' => 'Snack', 'slug' => 'snack']);
        $catDrink = \App\Models\Category::create(['name' => 'Drink', 'slug' => 'drink']);
        $catFood = \App\Models\Category::create(['name' => 'Food', 'slug' => 'food']);
        $catDairy = \App\Models\Category::create(['name' => 'Dairy', 'slug' => 'dairy']);
        $catHousehold = \App\Models\Category::create(['name' => 'Household', 'slug' => 'household']);

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
            // Add stock for each product
            \App\Models\Stock::create([
                'product_id' => $product->id,
                'branch_id' => $pusat->id,
                'quantity' => rand(50, 200)
            ]);
        }

        // Customers
        \App\Models\Customer::create([
            'name' => 'John Doe',
            'phone' => '081234567890',
            'email' => 'john@example.com',
            'address' => 'Jl. Contoh No. 123',
            'points' => 100,
            'member_since' => now()->subMonths(6),
        ]);

        \App\Models\Customer::create([
            'name' => 'Jane Smith',
            'phone' => '081234567891',
            'email' => 'jane@example.com',
            'address' => 'Jl. Sample No. 456',
            'points' => 250,
            'member_since' => now()->subYear(),
        ]);

        \App\Models\Customer::create([
            'name' => 'Bob Wilson',
            'phone' => '081234567892',
            'email' => 'bob@example.com',
            'address' => 'Jl. Test No. 789',
            'points' => 50,
            'member_since' => now()->subMonths(3),
        ]);

        // Payment Methods
        \App\Models\PaymentMethod::create(['name' => 'Cash', 'slug' => 'cash']);
        \App\Models\PaymentMethod::create(['name' => 'QRIS', 'slug' => 'qris']);
        \App\Models\PaymentMethod::create(['name' => 'Debit Card', 'slug' => 'debit']);
        \App\Models\PaymentMethod::create(['name' => 'Credit Card', 'slug' => 'credit']);
        \App\Models\PaymentMethod::create(['name' => 'E-Wallet', 'slug' => 'ewallet']);
    }
}
