# 📦 ARTIKA POS - Installation Guide

Panduan lengkap instalasi ARTIKA POS System dari awal (clone) hingga siap dijalankan.

---

## 📋 Daftar Isi

- [Prasyarat Sistem](#prasyarat-sistem)
- [Quick Start (Langkah Demi Langkah)](#quick-start-langkah-demi-langkah)
- [Detail Instalasi per Platform](#detail-instalasi-per-platform)
    - [Windows (Laragon - Recommended)](#windows-laragon---recommended)
    - [Windows (XAMPP)](#windows-xampp)
    - [Linux / macOS](#linux--macos)
- [Troubleshooting](#troubleshooting)

---

## Prasyarat Sistem

Pastikan perangkat Anda sudah terinstall tools berikut:

1.  **Git**: Untuk clone repository. [Download Git](https://git-scm.com/downloads)
2.  **PHP 8.2+**: Wajib versi 8.2 atau lebih baru.
3.  **Composer**: Dependency manager untuk PHP. [Download Composer](https://getcomposer.org/download/)
4.  **Node.js (LTS)**: Untuk compile aset frontend (Vite). [Download Node.js](https://nodejs.org/)
5.  **MySQL/MariaDB**: Database server.

---

## Quick Start (Langkah Demi Langkah)

Ikuti langkah-langkah ini secara berurutan untuk menjalankan aplikasi.

### 1. Clone Project
Buka terminal (Command Prompt, PowerShell, atau Git Bash) dan arahkan ke folder web server Anda (contoh: `C:\laragon\www`).

```bash
git clone https://github.com/yourusername/artika-pos.git ARTIKA
cd ARTIKA
```

### 2. Install Dependencies
Install library PHP dan Node.js yang dibutuhkan.

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Konfigurasi Environment (.env)
Copy file contoh konfigurasi dan buat file `.env` baru.

```bash
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

Buka file `.env` dengan text editor dan sesuaikan bagian Database:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika       <-- Pastikan nama database ini sama dengan yang akan dibuat
DB_USERNAME=root         <-- Default Laragon/XAMPP biasanya 'root'
DB_PASSWORD=             <-- Default Laragon/XAMPP biasanya kosong
```

### 4. Generate App Key
Generate encryption key untuk keamanan aplikasi.

```bash
php artisan key:generate
```

### 5. Setup Database
1.  Buka aplikasi database manager (HeidiSQL, phpMyAdmin, DBeaver, dll).
2.  Buat database baru dengan nama `artika` (sesuai setting `.env` tadi).
    ```sql
    CREATE DATABASE artika;
    ```
3.  Jalankan migrasi untuk membuat tabel dan mengisi data awal (seeder).

```bash
php artisan migrate:fresh --seed
```

> **Info:** Perintah ini akan membuat tabel dan mengisi data sample seperti user admin, kasir, produk, dll.

### 6. Jalankan Aplikasi
Anda perlu menjalankan **dua terminal** secara bersamaan.

**Terminal 1 (Vite Development Server):**
Untuk compile aset CSS/JS secara real-time.
```bash
npm run dev
```

**Terminal 2 (Laravel Server):**
Jika tidak menggunakan Virtual Host Laragon.
```bash
php artisan serve
```
Akses aplikasi di: [http://localhost:8000](http://localhost:8000)

---

## Detail Instalasi per Platform

### Windows (Laragon - Recommended)
Laragon sangat disarankan karena fiturnya yang lengkap dan mudah untuk Laravel.

1.  Buka **Terminal Laragon** (cmder).
2.  Masuk ke folder `www`: `cd C:\laragon\www`
3.  Jalankan langkah **Quick Start** nomor 1-5 di atas.
4.  **Virtual Host**:
    - Setelah folder `ARTIKA` ada di `www`, Laragon biasanya otomatis mendeteksi.
    - Reload Laragon (Stop & Start All).
    - Aplikasi bisa diakses langsung via **http://artika.test** (tanpa `php artisan serve`).
5.  Jangan lupa tetap jalankan `npm run dev` di terminal agar tampilan tidak berantakan.

#### Menggunakan phpMyAdmin di Laragon
Jika Anda lebih suka menggunakan **phpMyAdmin** daripada HeidiSQL:
1.  Pastikan phpMyAdmin sudah terinstall di Laragon (jika belum, download dari *Menu > Tools > Quick add > phpMyAdmin*).
2.  Akses di browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3.  Login default:
    - Username: `root`
    - Password: (kosong)
4.  Buat database `artika` di sini jika belum dibuat.

#### Kasus: Install Ulang Laragon / Pindah Laptop
Jika Anda menghapus/menginstall ulang Laragon, **database akan hilang**, tetapi file project di `www` biasanya aman (jika tidak dihapus manual).

**Langkah Pemulihan:**
1.  Install Laragon baru.
2.  Pastikan folder project `ARTIKA` ada di `C:\laragon\www\`.
3.  Start Laragon (Start All).
4.  Buka **phpMyAdmin** atau HeidiSQL.
5.  Buat ulang database `artika`.
6.  Buka terminal di folder project (`C:\laragon\www\ARTIKA`), lalu jalankan migrasi ulang:
    ```bash
    php artisan migrate:fresh --seed
    ```
    *(Ini akan membuat ulang tabel & data default. Data transaksi lama akan hilang kecuali Anda punya backup SQL).*
7.  Akses web kembali.

### Windows (XAMPP)
1.  Buka terminal/CMD, masuk ke `htdocs`: `cd C:\xampp\htdocs`.
2.  Jalankan langkah **Quick Start** nomor 1-5.
3.  Pastikan Apache & MySQL di Control Panel XAMPP sudah Start.
4.  Akses aplikasi bisa via `http://localhost/ARTIKA/public` atau gunakan `php artisan serve`.

### Linux / macOS
Mirip dengan Quick Start, namun perhatikan *permission* folder.

1.  Setelah clone dan install dependencies:
    ```bash
    chmod -R 775 storage bootstrap/cache
    ```
2.  Pastikan user web server memiliki akses write ke folder tersebut.

---

## Login Credentials (Default)

Setelah menjalankan `php artisan migrate:fresh --seed`, gunakan akun berikut untuk login:

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `password` | Akses penuh ke dashboard admin |
| **Kasir** | `kasir1` | `password` | Login menggunakan NIS: `12345` |
| **Gudang** | `gudang` | `password` | Akses manajemen stok |

---

## Troubleshooting

**1. Tampilan acak-acakan / CSS tidak load**
*   Pastikan command `npm run dev` sedang berjalan di terminal.
*   Jika di production, jalankan `npm run build`.

**2. Access denied for user 'root'@'localhost'**
*   Cek file `.env`, pastikan `DB_PASSWORD` kosong (jika default Laragon/XAMPP) atau sesuai password root Anda.

**3. Could not find driver (PDO Exception)**
*   Pastikan ekstensi PHP untuk MySQL aktif. Cek `php.ini` dan uncomment `extension=pdo_mysql`.

**4. 500 Server Error**
*   Cek file `.env` apakah sudah ada.
*   Cek key apakah sudah digenerate (`php artisan key:generate`).
*   Cek permission folder `storage` (terutama di Linux/Mac).

**5. Target class controller does not exist**
*   Coba jalankan: `composer dump-autoload`
*   Lalu: `php artisan route:clear`

---
**Happy Coding! 🚀**
