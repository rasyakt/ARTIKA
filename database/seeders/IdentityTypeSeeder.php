<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'nis', 'label' => 'NIS'],
            ['name' => 'nisn', 'label' => 'NISN'],
            ['name' => 'nik', 'label' => 'NIK'],
            ['name' => 'passport', 'label' => 'Passport'],
        ];

        foreach ($types as $type) {
            \App\Models\IdentityType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
