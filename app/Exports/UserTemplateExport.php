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

class UserTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new UserDataSheet(),
            new InstructionsSheet('User'),
        ];
    }
}

class UserDataSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Data User';
    }

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
