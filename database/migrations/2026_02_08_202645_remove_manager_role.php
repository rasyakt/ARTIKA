<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove manager role
        \Illuminate\Support\Facades\DB::table('roles')->where('name', 'manager')->delete();

        // Standardize descriptions
        $roles = [
            'superadmin' => 'Developer / System Administrator with full access to technical tools.',
            'admin' => 'Store Administrator with full access to management features.',
            'warehouse' => 'Warehouse Staff responsible for inventory and stock management.',
            'cashier' => 'Cashier responsible for POS transactions and sales.',
        ];

        foreach ($roles as $name => $desc) {
            \Illuminate\Support\Facades\DB::table('roles')->where('name', $name)->update([
                'description' => $desc,
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore manager role if needed
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert(
            ['name' => 'manager'],
            [
                'description' => 'Store Manager with limited administrative access.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
};
