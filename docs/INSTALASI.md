# 🔧 Panduan Instalasi ARTIKA POS

Ikuti langkah-langkah di bawah ini untuk menginstal ARTIKA POS di lingkungan pengembangan lokal Anda (Localhost).

---

## 📋 Prasyarat Sistem

Sebelum memulai, pastikan perangkat Anda sudah terinstal:

1. **PHP 8.2** atau versi terbaru.
2. **Composer** (Dependency manager untuk PHP).
3. **Node.js & NPM** (Untuk kompilasi asset frontend).
4. **MySQL/MariaDB** (Sebagai basis data).
5. **Web Server** (Sangat disarankan menggunakan **Laragon** atau **XAMPP** di Windows).

---

## 🚀 Langkah Instalasi

### 1. Menyiapkan Repository

Buka terminal/CMD dan jalankan perintah berikut:

```bash
git clone https://github.com/username/artika-pos.git
cd artika-pos
```

### 2. Menginstal Dependensi

Instal pustaka PHP dan JavaScript yang dibutuhkan:

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`, lalu buat kunci aplikasi:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Pengaturan Database

Buka file `.env` dan sesuaikan bagian database berikut sesuai dengan konfigurasi server lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika_pos
DB_USERNAME=root
DB_PASSWORD=
```

_Catatan: Pastikan Anda sudah membuat database bernama `artika_pos` di PHPMyAdmin atau MySQL._

### 5. Migrasi & Seeder

Jalankan perintah ini untuk membuat struktur tabel dan mengisi data awal (user admin, kategori, dll):

```bash
php artisan migrate --seed
```

### 6. Menjalankan Aplikasi

Buka dua terminal berbeda:

- **Terminal 1 (Kompilasi Asset):** `npm run dev`
- **Terminal 2 (Server PHP):** `php artisan serve`

Akses aplikasi melalui browser dialamat: `http://localhost:8000`

---

## 🛠️ Tips Troubleshooting

- Jika gambar tidak muncul, jalankan: `php artisan storage:link`.
- Jika terjadi error "Class not found", jalankan: `composer dump-autoload`.
- Pastikan ekstensi PHP `gd`, `bcmath`, dan `intl` sudah aktif.

---

**Tim Support ARTIKA POS**
