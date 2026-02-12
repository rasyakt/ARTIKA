# 🔧 Panduan Instalasi ARTIKA POS

Ikuti langkah-langkah di bawah ini untuk menginstal ARTIKA POS di lingkungan pengembangan lokal Anda (Localhost).

---

## 📋 Prasyarat Sistem

Sebelum memulai, pastikan perangkat Anda sudah terinstal:

1. **PHP 8.2** atau versi terbaru.
2. **Composer** (Dependency manager untuk PHP).
3. **Node.js 18+ & NPM** (Untuk kompilasi asset frontend).
4. **MySQL 5.7+ / PostgreSQL** (Sebagai basis data).
5. **Web Server** (Sangat disarankan memakai **Laragon** untuk Windows).

---

## 🚀 Langkah Instalasi

### 1. Menyiapkan Repository

Buka terminal/CMD dan jalankan perintah berikut:

```bash
git clone https://github.com/rasyakt/ARTIKA.git
cd ARTIKA
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

Buka file `.env` dan sesuaikan bagian database berikut sesuai dengan konfigurasi server lokal Anda (MySQL/PostgreSQL):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi & Seeder

Jalankan perintah ini untuk membuat struktur tabel dan mengisi data awal:

```bash
php artisan migrate --seed
```

### 6. Kompilasi & Jalankan

Untuk pertama kali, bangun asset frontend:

```bash
npm run build
```

Lalu jalankan server lokal:

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 🛠️ Tips Troubleshooting

- **Symlink Storage**: Jika gambar tidak muncul, jalankan: `php artisan storage:link`.
- **Cache**: Jika perubahan tidak terlihat, jalankan: `php artisan optimize`.
- **Database**: Pastikan database sudah dibuat di server (PHPMyAdmin) sebelum menjalankan migrasi.

---

**Tim Support ARTIKA POS**
