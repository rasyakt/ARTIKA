<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nama_lengkap',
            'username',
            'nomor_identitas',
            'password',
            'nama_role',
            'jenis_identitas'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                'budikasir',
                '12345678',
                'password123',
                'cashier',
                'NIS'
            ],
            [
                'Siti Gudang',
                'sitigudang',
                '87654321',
                'password123',
                'warehouse',
                'NIK'
            ]
        ];
    }
}
