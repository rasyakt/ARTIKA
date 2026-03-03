<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ProductDataSheet(),
            new InstructionsSheet('Produk'),
        ];
    }
}

class ProductDataSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Data Produk';
    }

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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2F5597']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }
}
