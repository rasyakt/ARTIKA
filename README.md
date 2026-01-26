# 🛒 Sistem POS ARTIKA

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-4479A1?style=flat&logo=postgresql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap)
![Vite](https://img.shields.io/badge/Vite-7.x-purple.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

**ARTIKA POS** adalah sistem kasir (Point of Sale) modern, ringan, dan responsif yang dirancang khusus untuk efisiensi transaksi, manajemen stok real-time, dan transparansi laporan keuangan. Dibangun dengan teknologi terbaru untuk memberikan pengalaman pengguna yang cepat dan stabil.

---

## Fitur Unggulan

- **POS Super Cepat**: Mendukung scanner barcode (USB/Kamera), keyboard shortcuts, dan pencarian produk instan.
- **Manajemen Stok Pintar**: Pantau stok masuk, keluar, dan peringatan stok rendah secara otomatis.
- **Laporan Lengkap**: Ekspor laporan harian, peringkat produk terlaris, dan audit log ke format PDF/CSV.
- **Akuntansi Otomatis**: Setiap transaksi langsung tercatat ke dalam Jurnal Keuangan (Debit/Kredit).
- **Multi-Role**: Akses terbatas berdasarkan peran (Admin, Kasir, Petugas Gudang).
- **Responsive Design**: Tampilan yang optimal di perangkat desktop, tablet, maupun ponsel.
- **Struk Profesional**: Cetak struk belanja dengan layout yang bersih dan informatif.

---

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade, Tailwind CSS 4, Bootstrap 5, Vite 7
- **Database**: MySQL / MariaDB / PostgreeSQL
- **Reporting**: Barryvdh Laravel DomPDF
- **Icons & UI**: FontAwesome 6, SweetAlert2, Google Fonts (Inter/Outfit)

---

## Instalasi Cepat

Ikuti langkah-langkah ini untuk menjalankan proyek di lokal Anda:

1. **Clone Repository**:
    ```bash
    git clone https://github.com/rasyakt/ARTIKA.git
    cd ARTIKA
    ```
2. **Setup Environment**:
    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    ```
3. **Database & Seeding**:
   _Pastikan database sudah dibuat di MySQL._
    ```bash
    php artisan migrate --seed
    ```
4. **Jalankan Aplikasi**:
   _Terminal 1:_ `npm run dev`
   _Terminal 2:_ `php artisan serve`

Akses di: `http://localhost:8000`

---

## Dokumentasi Lengkap

Untuk informasi lebih mendalam, silakan baca panduan berikut:

- [**Panduan Instalasi Detail**](docs/INSTALASI.md) - Langkah instalasi dan troubleshooting.
- [**Arsitektur Sistem**](docs/ARSITEKTUR.md) - Detail teknis, pola desain, dan alur data.
- [**Skema Database**](docs/DATABASE.md) - Struktur tabel dan relasi antar data.
- [**User Guide: Administrator**](docs/USER_GUIDE_ADMIN.md) - Panduan kelola user dan laporan.
- [**User Guide: Kasir**](docs/USER_GUIDE_CASHIER.md) - Panduan transaksi dan POS.
- [**User Guide: Gudang**](docs/USER_GUIDE_WAREHOUSE.md) - Panduan manajemen stok.
- [**Panduan Deployment**](docs/DEPLOYMENT.md) - Cara hosting aplikasi di server/cPanel.

---

## Kontribusi

Kontribusi selalu terbuka! Jika Anda ingin meningkatkan ARTIKA POS:

1. Fork repository ini.
2. Buat branch fitur baru (`git checkout -b fitur/HebatBaru`).
3. Commit perubahan Anda (`git commit -m 'Menambahkan fitur HebatBaru'`).
4. Push ke branch (`git push origin fitur/HebatBaru`).
5. Buat Pull Request.

---

## Lisensi

Proyek ini dilisensikan di bawah **MIT License**. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---

**Dibuat oleh Tim RPL Sentinel 2026**
