<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Pcs', 'short_name' => 'pcs'],
            ['name' => 'Box', 'short_name' => 'box'],
            ['name' => 'Pack', 'short_name' => 'pack'],
            ['name' => 'Dus', 'short_name' => 'dus'],
            ['name' => 'Lusin', 'short_name' => 'lsn'],
            ['name' => 'Kg', 'short_name' => 'kg'],
            ['name' => 'Gram', 'short_name' => 'gr'],
            ['name' => 'Liter', 'short_name' => 'ltr'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
