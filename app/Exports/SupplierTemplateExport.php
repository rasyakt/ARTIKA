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

class SupplierTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SupplierDataSheet(),
            new InstructionsSheet('Pemasok'),
        ];
    }
}

class SupplierDataSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Data Pasokan';
    }

    public function headings(): array
    {
        return [
            'barcode',
            'nama_produk',
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
                '8993420000000',
                'Parfume B&B',
                'Pcs',
                '1',
                '50',
                '7800',
                'Stok tambahan awal bulan'
            ],
            [
                '8993160000000',
                'Tissue Rumah Tangga',
                'Pack',
                '6',
                '10',
                '25000',
                ''
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
