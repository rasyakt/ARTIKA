# 🛒 Sistem POS ARTIKA

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15.0-4479A1?style=for-the-badge&logo=postgresql&logoColor=white)

**Sistem Kasir (Point of Sale) Modern, Ringan & Responsif**  
ARTIKA POS - SMKN 1 Ciamis

[Fitur](#fitur-utama) • [Instalasi](#instalasi) • [Dokumentasi](#dokumentasi) • [Team](#team)

</div>

---

## Tentang Project

**ARTIKA POS** adalah sistem kasir (Point of Sale) yang dirancang khusus untuk efisiensi transaksi, manajemen stok real-time, dan transparansi laporan keuangan. Aplikasi ini memberikan pengalaman pengguna yang cepat dan stabil dengan antarmuka yang modern dan responsif di berbagai perangkat.

### Tujuan Project

- Digitalisasi proses transaksi kasir agar lebih cepat dan akurat.
- Otomatisasi manajemen stok dan peringatan barang menipis.
- Integrasi laporan keuangan (Jurnal) otomatis dari setiap transaksi.
- Mempermudah pengawasan operasional bagi Manajer dan Admin.
- Meningkatkan efisiensi layanan melalui dukungan scanner dan shortcut keyboard.

---

## Fitur Utama

### Point of Sale (POS)

- **Transaksi Kilat**: Dukungan Scanner Barcode (USB/Kamera) dan pencarian produk instan.
- **Smart Keyboard Shortcuts**: Kendalikan aplikasi sepenuhnya dengan keyboard (F1-F9, Alt+C).
- **Fitur Tunda (Hold)**: Parkir transaksi pelanggan sementara tanpa mengganggu antrian.
- **Auto-Focus Logic**: Fokus kursor otomatis kembali ke barcode setelah transaksi selesai.

### Manajemen Stok & Gudang

- **Inventory Real-time**: Pantau stok masuk, keluar, dan mutasi secara otomatis.
- **Peringatan Stok Minim**: Notifikasi visual untuk barang yang perlu segera dipesan ulang.
- **Supplier & Purchases**: Kelola data pemasok dan catat pembelian stok massal secara terstruktur.
- **Import Massal Excel**: Tambah ratusan produk sekaligus melalui template Excel yang aman.

### Laporan & Keuangan

- **Reports Hub**: Dashboard laporan terpusat (Warehouse, Cashier, Finance).
- **Akuntansi Otomatis**: Setiap transaksi otomatis tercatat ke dalam Jurnal Keuangan (Debit/Kredit).
- **Ekspor Dokumen**: Cetak struk belanja dan ekspor laporan ke format PDF atau CSV.
- **Manajemen Biaya**: Dashboard khusus untuk mencatat pengeluaran operasional toko.

### Keamanan & Audit

- **Multi-Role Access**: 5 level akses (Superadmin, Admin, Manager, Cashier, Warehouse).
- **Audit Log Detail**: Rekam jejak aktivitas user (IP, Device, Action, URL) untuk transparansi.
- **Secure File Handling**: Proteksi upload gambar dengan _re-encoding_ dan _secure Excel parsing_.

---

## Tech Stack

### Backend

- **Framework**: Laravel 12.x
- **Database**: PostgreSQL / MySQL / MariaDB
- **Reporting**: Barryvdh Laravel DomPDF
- **Excel Library**: Maatwebsite Excel

### Frontend

- **CSS Framework**: Bootstrap 5.3
- **Icons**: FontAwesome 6
- **UI Components**: SweetAlert2, Google Fonts (Inter/Outfit)
- **Build Tool**: Vite 7.x

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/rasyakt/ARTIKA.git
cd ARTIKA
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy .env example
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Edit file `.env` dan sesuaikan credentials database Anda (PostgreSQL/MySQL):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=artika_pos
DB_USERNAME=postgres
DB_PASSWORD=
```

Jalankan migrasi dan seeder:

```bash
# Isi data sistem (Role, Superadmin, Payment)
php artisan migrate --seed --class=RoleAndSystemSeeder

# (Opsional) Isi data demo untuk pengembangan
php artisan db:seed --class=DemoDataSeeder
```

### 5. Storage Link & Build

```bash
php artisan storage:link
npm run build
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

---

## Default Credentials

| Role            | Username     | Password        |
| --------------- | ------------ | --------------- |
| **Super Admin** | `superadmin` | `superadmin123` |

> **⚠️ PERINGATAN**: Segera ganti password Super Admin setelah login pertama kali di menu Pengaturan.

---

## Dokumentasi Lengkap

- [**Arsitektur Sistem**](docs/ARSITEKTUR.md) - Detail teknis dan pola desain.
- [**Skema Database**](docs/DATABASE.md) - Relasi antar tabel dan data.
- [**Panduan Instalasi**](docs/INSTALASI.md) - Troubleshooting detail.
- [**Panduan Deployment**](docs/DEPLOYMENT.md) - Hosting ke server sekolah.
- [**User Guide Kasir**](docs/USER_GUIDE_CASHIER.md) - Operasional POS.
- [**User Guide Warehouse**](docs/USER_GUIDE_WAREHOUSE.md) - Operasional Gudang.
- [**User Guide Manager**](docs/USER_GUIDE_MANAGER.md) - Operasional Manager.
- [**User Guide Admin**](docs/USER_GUIDE_ADMIN.md) - Operasional Admin.
- [**User Guide Super Admin**](docs/USER_GUIDE_SUPER_ADMIN.md) - Operasional Super Admin.

---

## Team

Project ini dikembangkan oleh Tim RPL Sentinel SMKN 1 Ciamis:

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/rasyakt" target="_blank">
        <img src="https://github.com/rasyakt.png" width="100px;" alt="Rasya Syahreza Maulana Zen"/><br />
        <sub><b>Rasya Syahreza Maulana Zen</b></sub>
      </a><br />
      <sub>Full Stack Developer</sub>
    </td>
    <td align="center">
      <a href="https://github.com/rafliaditya0125" target="_blank">
        <img src="https://github.com/rafliaditya0125.png" width="100px;" alt="Rafli Aditya"/><br />
        <sub><b>Rafli Aditya</b></sub>
      </a><br />
      <sub>Backend Developer</sub>
    </td>
    <td align="center">
      <a href="https://github.com/ZidnyAl-HikamMawarist" target="_blank">
        <img src="https://github.com/ZidnyAl-HikamMawarist.png" width="100px;" alt="Zidny Al-Hikam Mawarist"/><br />
        <sub><b>Zidny Al-Hikam Mawarist</b></sub>
      </a><br />
      <sub>System Designer & QA</sub>
    </td>
    <td align="center">
      <a href="https://github.com/Ejakbarr" target="_blank">
        <img src="https://github.com/Ejakbarr.png" width="100px;" alt="Fahreza Akbar Maulana"/><br />
        <sub><b>Fahreza Akbar Maulana</b></sub>
      </a><br />
      <sub>UI/UX Designer</sub>
    </td>
  </tr>
</table>

### Institusi

- **SMKN 1 Ciamis**  
  Jl. Jenderal Sudirman №269, Kelurahan Sindangrasa, Kecamatan Ciamis, Kabupaten Ciamis, Jawa Barat

---

## License

This project is **proprietary** software developed for SMKN 1 Ciamis / ARTIKA SMKN 1 Ciamis.

All rights reserved © 2026 Tim RPL Sentinel

---

<div align="center">

**Developed by Tim RPL Sentinel**

© 2026 RPL SMKN 1 Ciamis

[Kembali ke atas](#-sistem-pos-artika)

</div>
