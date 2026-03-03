<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstructionsSheet implements FromArray, WithStyles, ShouldAutoSize, WithTitle
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function title(): string
    {
        return 'PETUNJUK';
    }

    public function array(): array
    {
        if ($this->type === 'Produk') {
            return [
                ['📌 PETUNJUK PENGISIAN:'],
                ['1. Isi data pada sheet pertama (Data Produk).'],
                ['2. Hapus baris contoh (baris 2-3) sebelum mengisi data baru.'],
                ['3. Kolom "barcode" dan "nama_produk" WAJIB diisi.'],
                ['4. Produk akan diperbarui (update) jika barcode sudah ada di database.'],
                ['5. Format harga hanya berupa angka saja (tanpa Rp atau titik/koma).'],
                ['6. Pastikan nama Kategori sudah ada di sistem, atau sistem akan otomatis menambahkannya.'],
                ['7. Simpan file dalam format .xlsx sebelum mengimpor.']
            ];
        }

        if ($this->type === 'Pemasok') {
            return [
                ['📌 PETUNJUK PENGISIAN:'],
                ['1. Isi data pada sheet pertama (Data Pasokan).'],
                ['2. Hapus baris contoh (baris 2-3) sebelum mengisi data baru.'],
                ['3. barcode WAJIB diisi sesuai dengan barcode produk di sistem.'],
                ['4. "pcs_per_satuan": Jika beli per Dus isi 24, maka isi 24 (default 1).'],
                ['5. "jumlah": Berapa banyak satuan (Dus/Pack) yang dibeli.'],
                ['6. "harga_beli_per_pcs": Harga beli per pcs.'],
                ['7. Simpan file dalam format .xlsx sebelum mengimpor.']
            ];
        }

        if ($this->type === 'User') {
            return [
                ['📌 PETUNJUK PENGISIAN:'],
                ['1. Isi data pada sheet pertama (Data User).'],
                ['2. Hapus baris contoh (baris 2-3) sebelum mengisi data baru.'],
                ['3. Kolom "nama_lengkap" dan "username" WAJIB diisi.'],
                ['4. Username harus unik (tidak boleh sama dengan yang sudah ada). Akun akan diupdate jika username ditemukan.'],
                ['5. Nama Role yang tersedia: superadmin, admin, manager, warehouse, cashier.'],
                ['6. Jenis Identitas (pilihan): KTP, SIM, NIS, NIK.'],
                ['7. Simpan file dalam format .xlsx sebelum mengimpor.']
            ];
        }

        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
