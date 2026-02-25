<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // CATEGORY: GENERAL
            [
                'question' => 'Bagaimana cara mengganti tema atau bahasa di ARTIKA POS?',
                'answer' => "Anda dapat mengganti tema (Terang, Gelap, atau Sistem) dan bahasa (Indonesia, Inggris) melalui menu Profil yang berada di pojok kanan atas layar.\n\n1. Klik nama profil Anda di pojok kanan atas.\n2. Di bagian 'Pengaturan', pilih ikon matahari untuk mode terang, bulan untuk mode gelap, atau desktop untuk mengikuti sistem.\n3. Untuk bahasa, klik tombol 'English' atau 'Indonesia'. Pengaturan ini akan tersimpan otomatis di perangkat Anda.",
                'category' => 'general',
                'target_role' => null,
                'sort_order' => 1,
            ],
            [
                'question' => 'Saya lupa password atau tidak bisa login, apa yang harus dilakukan?',
                'answer' => "Jika Anda tidak dapat masuk ke sistem, silakan hubungi Superadmin atau Developer ARTIKA. \n\nAkun Anda mungkin terkunci karena alasan keamanan atau memerlukan reset password dari panel User Management yang hanya bisa diakses oleh role Admin/Superadmin.",
                'category' => 'general',
                'target_role' => null,
                'sort_order' => 2,
            ],

            // CATEGORY: POS (KASIR)
            [
                'question' => 'Bagaimana cara menggunakan scanner kamera untuk scan produk?',
                'answer' => "Di halaman POS, Anda bisa menggunakan kamera HP/Laptop sebagai scanner barcode:\n\n1. Klik ikon kamera di sebelah kolom pencarian produk.\n2. Jika browser meminta izin, pilih 'Allow/Izinkan'.\n3. Arahkan barcode produk ke kotak scanner yang muncul.\n4. Jika berhasil, sistem akan berbunyi 'beep' dan produk otomatis masuk ke keranjang.\n5. Anda bisa menutup scanner dengan klik tombol 'Tutup' atau area di luar kotak.",
                'category' => 'pos',
                'target_role' => 'cashier',
                'sort_order' => 1,
            ],
            [
                'question' => 'Apa fungsi fitur "Tahan" (Hold) transaksi?',
                'answer' => "Fitur Tahan digunakan jika pelanggan ingin menunda pembayaran sementara:\n\n1. Masukkan produk ke keranjang seperti biasa.\n2. Klik tombol 'Tahan' (ikon pause).\n3. Keranjang akan dikosongkan untuk melayani antrean berikutnya.\n4. Untuk melanjutkan, klik ikon jam (Riwayat/Held), cari transaksinya, lalu klik 'Resume'.",
                'category' => 'pos',
                'target_role' => 'cashier',
                'sort_order' => 2,
            ],
            [
                'question' => 'Bagaimana cara memproses pembayaran non-tunai (QRIS/Debit)?',
                'answer' => "Sistem mendukung berbagai metode pembayaran:\n\n1. Klik tombol 'Checkout' di keranjang.\n2. Pilih metode pembayaran (QRIS, Debit, atau E-Wallet).\n3. Masukkan jumlah bayar sesuai total (atau klik 'Uang Pas').\n4. Klik 'Proses Pembayaran'.\n5. Struk akan otomatis tercetak dan transaksi tersimpan sebagai non-tunai di laporan keuangan.",
                'category' => 'pos',
                'target_role' => 'cashier',
                'sort_order' => 3,
            ],

            // CATEGORY: WAREHOUSE (GUDANG)
            [
                'question' => 'Bagaimana cara menambah stok barang masuk?',
                'answer' => "Untuk menambah stok barang yang baru datang:\n\n1. Buka menu 'Manajemen Stok'.\n2. Cari nama produk atau scan barcodenya.\n3. Klik tombol 'Update/Sesuaikan'.\n4. Pilih tipe pergerakan 'Stok Masuk', masukkan jumlah barang, dan simpan.\n5. Riwayat ini akan tersatat di 'Stock Movements' sebagai bukti audit.",
                'category' => 'warehouse',
                'target_role' => 'warehouse',
                'sort_order' => 1,
            ],
            [
                'question' => 'Mengapa saya menerima notifikasi "Stok Rendah"?',
                'answer' => "Sistem ARTIKA memantau jumlah stok secara real-time. Jika stok produk turun di bawah batas minimum yang ditentukan (misal: di bawah 5 pcs), sistem akan menandainya di menu 'Peringatan Stok Rendah'. Hal ini bertujuan agar Anda segera melakukan restock agar tidak kehilangan potensi penjualan.",
                'category' => 'warehouse',
                'target_role' => 'warehouse',
                'sort_order' => 2,
            ],

            // CATEGORY: ADMIN
            [
                'question' => 'Bagaimana cara melakukan Rollback transaksi yang salah?',
                'answer' => "Jika terjadi kesalahan input oleh kasir yang baru disadari setelah transaksi selesai:\n\n1. Masuk ke menu 'Laporan Kasir'.\n2. Cari transaksi tersebut berdasarkan ID Transaksi atau waktu.\n3. Klik tombol merah 'Rollback'.\n4. Sistem akan otomatis membatalkan transaksi, mengembalikan stok barang ke gudang, dan mengurangi saldo kas hari ini.",
                'category' => 'admin',
                'target_role' => 'admin',
                'sort_order' => 1,
            ],
            [
                'question' => 'Apakah saya bisa mengubah format nomor faktur (Invoice)?',
                'answer' => "Ya, Superadmin dapat mengatur ini di menu 'Advanced Settings'. Anda dapat mengubah Prefix (awalan) nomor faktur agar sesuai dengan identitas toko Anda.",
                'category' => 'admin',
                'target_role' => 'superadmin',
                'sort_order' => 2,
            ],

            // CATEGORY: MANAGER (KEPALA TOKO)
            [
                'question' => 'Di mana saya bisa melihat performa penjualan harian?',
                'answer' => "Manajer memiliki Dashboard khusus yang menampilkan grafik penjualan harian, produk terlaris, dan performa kasir. Anda juga bisa mengekspor laporan lengkap ke format Excel melalui menu 'Laporan Keuangan'.",
                'category' => 'manager',
                'target_role' => 'manager',
                'sort_order' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'category' => $faq['category'],
                    'target_role' => $faq['target_role'],
                    'sort_order' => $faq['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
