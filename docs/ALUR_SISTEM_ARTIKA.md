# Alur Kerja Sistem Artika

## 1. Akses & Login

Sistem membedakan pintu masuk agar setiap peran fokus pada tugasnya masing-masing.

- **Staf/Kasir**: Login melalui halaman utama biasa (`artika.smkn1ciamis.id/login`).
- **Admin & Manajer**: Menggunakan akses khusus (`artika.smkn1ciamis.id/login/admin`) untuk pengelolaan.
- **Gudang**: Menggunakan jalur khusus (`artika.smkn1ciamis.id/login/warehouse`) agar fokus pada logistik.

Setelah login, pengguna langsung diarahkan ke dasbor kerja mereka. Sistem memisahkan area ini agar tidak ada akses yang tertukar.

## 2. Kasir (Transaksi Penjualan)

Area operasional utama untuk melayani pelanggan secara efisien.

- **Input Cepat**: Kasir cukup scan barcode atau cari nama produk. Harga dan diskon otomatis terhitung sistem.
- **Fitur Tunda (Hold)**: Transaksi bisa ditunda sementara jika pelanggan ingin menambah pesanan, tanpa perlu membatalkan antrian yang sedang berjalan.
- **Penyelesaian**: Saat pembayaran selesai, stok otomatis berkurang dari gudang dan nominal tercatat di laporan harian.

## 3. Gudang (Manajemen Stok)

Tim gudang memastikan ketersediaan barang tetap terjaga.

- **Monitor Stok**: Melihat jumlah persediaan secara langsung (_real-time_) di layar.
- **Peringatan Stok Minim**: Sistem memberikan notifikasi otomatis jika ada barang yang jumlahnya menipis, memudahkan proses restock.
- **Penyesuaian (Stock Opname)**: Tim gudang dapat menyesuaikan angka di sistem agar akurat dengan fisik barang jika terjadi selisih (rusak/hilang).

## 4. Manajer (Pengawasan)

Manajer memantau seluruh operasional toko tanpa harus turun tangan teknis setiap saat.

- **Laporan Harian**: Memantau omzet harian dan produk terlaris secara langsung tanpa rekap manual.
- **Koreksi & Pembatalan**: Hanya Manajer yang memiliki wewenang untuk mengedit atau membatalkan transaksi yang sudah terjadi demi keamanan data.
- **Audit Log**: Memantau rekam jejak aktivitas pengguna untuk mencegah dan menelusuri kesalahan atau kecurangan.

## 5. Admin (Pusat Kontrol)

Pusat pengaturan untuk data induk dan kebijakan toko.

- **Manajemen Produk**: Menambah produk baru, mengatur harga jual, serta mengelola kategori barang.
- **Promo & Diskon**: Mengatur program promosi yang akan berjalan.
- **Manajemen Pengguna**: Mendaftarkan akun karyawan baru dan menentukan peran mereka (Kasir, Gudang, dll).
- **Laporan Keuangan**: Akses penuh ke laporan laba rugi untuk analisis bisnis jangka panjang.

## 6. Superadmin (Teknis & Perawatan)

Akses level tinggi untuk pemeliharaan kesehatan sistem.

- **Optimasi Sistem**: Membersihkan data sampah (_cache_) agar performa aplikasi tetap cepat.
- **Mode Perbaikan**: Menutup akses sementara saat sistem sedang dalam perbaikan besar (_maintenance mode_).
