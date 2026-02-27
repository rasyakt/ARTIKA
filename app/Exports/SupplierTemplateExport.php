<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'barcode',
            'nama_produk', // Visual reference only
            'satuan',
            'pcs_per_satuan',
            'jumlah',
            'harga_beli_per_pcs',
            'catatan'
        ];
    }

    public function array(): array
    {
        return [
            [
                '8993420000000', // Example barcode
                'Parfume B&B',
                'Pcs',
                '1',
                '50',
                '7800',
                'Stok tambahan awal bulan'
            ],
            [
                '8993160000000', // Example barcode
                'Tissue wa Rumah Ta',
                'Pack',
                '6',
                '10',
                '25000',
                ''
            ]
        ];
    }
}
