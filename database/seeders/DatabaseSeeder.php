<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\PaymentMethod;
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
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin'], [
            'description' => 'Developer / System Administrator with full access to technical tools.'
        ]);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['description' => 'Kepala Toko']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier'], ['description' => 'Kasir']);
        $warehouseRole = Role::firstOrCreate(['name' => 'warehouse'], ['description' => 'Staff Gudang']);

        // Users
        // 1. Superadmin
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Superadmin Developer',
                'password' => bcrypt('superadmin nih bos senggol dong'),
                'role_id' => $superadminRole->id,
            ]
        );

        // 2. Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // password
                'role_id' => $adminRole->id,
            ]
        );

        // 3. Cashier
        User::firstOrCreate(
            ['username' => 'kasir1'],
            [
                'name' => 'Kasir 01',
                'nis' => '12345',
                'password' => bcrypt('password'),
                'role_id' => $cashierRole->id,
            ]
        );

        // 4. Warehouse
        User::firstOrCreate(
            ['username' => 'gudang'],
            [
                'name' => 'Staff Gudang',
                'password' => bcrypt('password'),
                'role_id' => $warehouseRole->id,
            ]
        );
        // Categories
        $catSnack = Category::create(['name' => 'Snack', 'slug' => 'snack']);
        $catDrink = Category::create(['name' => 'Minuman', 'slug' => 'drink']);
        $catFood = Category::create(['name' => 'Makanan', 'slug' => 'food']);
        $catDairy = Category::create(['name' => 'Dairy', 'slug' => 'dairy']);
        $catHousehold = Category::create(['name' => 'Peralatan', 'slug' => 'household']);

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
            $product = Product::create($productData);
            // Add stock for product
            Stock::create([
                'product_id' => $product->id,
                'quantity' => rand(50, 200)
            ]);
        }

        // Sample Suppliers (replacing Customers seeds)
        Supplier::create([
            'name' => 'CV. Sumber Jaya',
            'phone' => '081298765432',
            'email' => 'sumberjaya@example.com',
            'address' => 'Jl. Supplier No. 10',
            'last_purchase_at' => now()->subDays(10),
        ]);

        Supplier::create([
            'name' => 'UD. Maju Sentosa',
            'phone' => '081233344455',
            'email' => 'maju@example.com',
            'address' => 'Jl. Supplier No. 20',
            'last_purchase_at' => now()->subMonths(1),
        ]);

        Supplier::create([
            'name' => 'PT. Grosir Utama',
            'phone' => '081277788899',
            'email' => 'grosir@example.com',
            'address' => 'Jl. Supplier No. 30',
            'last_purchase_at' => now()->subMonths(2),
        ]);

        // Payment Methods
        PaymentMethod::create(['name' => 'Cash', 'slug' => 'cash']);
        PaymentMethod::create(['name' => 'QRIS', 'slug' => 'qris']);
        PaymentMethod::create(['name' => 'Debit Card', 'slug' => 'debit']);
        PaymentMethod::create(['name' => 'Credit Card', 'slug' => 'credit']);
        PaymentMethod::create(['name' => 'E-Wallet', 'slug' => 'ewallet']);
    }
}
