<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'barcode',
            'nama_produk',
            'kategori',
            'harga_jual',
            'harga_modal',
            'deskripsi'
        ];
    }

    public function array(): array
    {
        return [
            [
                '8991234567890',
                'Indomie Noodle Goreng',
                'Makanan',
                '3500',
                '2500',
                'Mie instan goreng favorit'
            ],
            [
                '8990987654321',
                'Aqua Botol 600ml',
                'Minuman',
                '4000',
                '3000',
                'Air mineral murni'
            ]
        ];
    }
}
