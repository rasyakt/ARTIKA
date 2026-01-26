# 🛒 Sistem POS ARTIKA

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap)

**ARTIKA** adalah sistem Point of Sale (POS) lengkap dan modern yang dibangun dengan Laravel 12. Dirancang khusus untuk retail dengan fitur canggih seperti scan barcode, kontrol akses berbasis peran (RBAC), dan manajemen inventaris yang akurat.

---

## ✨ Fitur Utama

### 🎯 Point of Sale (POS)

- **Smart Barcode Scanner** - Anti-spam dan feedback audio-visual yang responsif.
- **Pencarian Produk** - Cari produk secara real-time berdasarkan nama, barcode, atau kategori.
- **Manajemen Keranjang** - Dioptimalkan untuk kecepatan transaksi.
- **Alur Pembayaran Terpadu** - Mendukung Tunai & Non-Tunai dengan unggah bukti bayar.
- **Tunda Transaksi** - Simpan dan lanjutkan transaksi dengan mudah.
- **Shortcut Keyboard** - Mempercepat kerja kasir (F2, F4, F8, Esc).
- **Cetak Struk Calibrated** - Dioptimalkan khusus untuk printer termal 58mm (VSC TM-58D).

### 👨‍💼 Dashboard Admin

- **Manajemen Produk** - CRUD produk lengkap dengan generator barcode.
- **Manajemen Kategori** - Pengelompokan produk yang dinamis.
- **Manajemen Pengguna** - Kelola akun dengan peran Admin, Kasir, atau Gudang.
- **Laporan Penjualan** - Analisis pendapatan harian dan bulanan yang mendalam.
- **Audit Log** - Pantau seluruh aktivitas penting dalam sistem.

### 📦 Manajemen Gudang

- **Kontrol Stok** - Pantau tingkat stok secara real-time.
- **Riwayat Mutasi** - Lacak setiap barang masuk dan keluar secara mendetail.
- **Penyesuaian Stok** - Fitur koreksi stok manual dengan catatan alasan.
- **Ekspor Dokumen** - Dukungan cetak PDF dan ekspor data ke Excel/CSV.

---

## 🚀 Memulai (Instalasi Cepat)

### Prasyarat

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 5.7+
- Node.js & NPM

### Langkah Instalasi

```bash
# Clone repository
git clone https://github.com/username/artika-pos.git
cd artika-pos

# Install dependensi
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di file .env Anda, kemudian:
php artisan migrate --seed

# Compile asset & jalankan server
npm run build
php artisan serve
```

---

## 📚 Dokumentasi Lengkap

Kami menyediakan panduan mendetail untuk setiap aspek sistem dalam Bahasa Indonesia:

### 🔧 Panduan Teknis

- **[Panduan Instalasi](file:///c:/laragon/www/ARTIKA/docs/INSTALASI.md)** - Cara pasang di Windows/Linux.
- **[Arsitektur Sistem](file:///c:/laragon/www/ARTIKA/docs/ARSITEKTUR.md)** - Detail teknis MVC & Service Layer.
- **[Struktur Database](file:///c:/laragon/www/ARTIKA/docs/DATABASE.md)** - Penjelasan tabel dan relasi.
- **[Panduan Deployment](file:///c:/laragon/www/ARTIKA/docs/DEPLOYMENT.md)** - Tips untuk Go-Live (Produksi).

### 📖 Panduan Pengguna

- **[User Guide: Admin](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_ADMIN.md)**
- **[User Guide: Kasir](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_CASHIER.md)**
- **[User Guide: Gudang](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_WAREHOUSE.md)**

---

**Versi:** 2.5 (Stabel)  
**Status:** ✅ Siap Produksi & Teroptimasi  
**Pengembang:** © Tim RPL Sentinel 2026
