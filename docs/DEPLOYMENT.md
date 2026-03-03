# 🚀 Panduan Deployment ARTIKA POS ke artika.smkn1ciamis.id

Panduan lengkap langkah demi langkah untuk men-deploy ARTIKA POS ke server sekolah melalui **WinSCP + SSH**.

---

## 📋 Prasyarat Server (Wajib Tanya IT Sekolah!)

Pastikan server tujuan sudah memiliki:

| Komponen           | Versi Minimum | Catatan                                           |
| ------------------ | ------------- | ------------------------------------------------- |
| **PHP**            | 8.2+          | Cek: `php -v`                                     |
| **PostgreSQL**     | 12+           | Bisa juga MySQL 5.7+, tapi project ini PostgreSQL |
| **Composer**       | 2.x           | Cek: `composer -V`                                |
| **Node.js**        | 18+           | Cek: `node -v` (diperlukan untuk build asset)     |
| **NPM**            | 9+            | Cek: `npm -v`                                     |
| **Nginx / Apache** | Terbaru       | Web server                                        |
| **Git** (opsional) | 2.x           | Bisa juga upload manual via WinSCP                |

### Ekstensi PHP Wajib Aktif

Verifikasi di server via `php -m` atau `php --ini`:

- `pdo_pgsql` (untuk PostgreSQL) ATAU `pdo_mysql` (untuk MySQL)
- `gd` (untuk kompresi gambar produk & bukti bayar)
- `mbstring`
- `xml` dan `dom`
- `zip` (untuk Excel Import via Maatwebsite)
- `bcmath`
- `fileinfo`
- `openssl`
- `ctype`
- `tokenizer`
- `curl`

---

## 🗂️ TAHAP 1: Persiapan di Komputer Lokal (Windows)

### 1.1. Build Asset Frontend

Buka terminal di folder project, jalankan:

```bash
npm install
npm run build
```

> **Penting:** Pastikan folder `public/build/` berisi file `manifest.json` dan folder `assets/` setelah build. Tanpa ini, seluruh CSS dan JS tidak akan tampil.

### 1.2. Optimasi Laravel

```bash
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

> `--no-dev` akan menghapus library testing (PHPUnit, Faker, dll) sehingga ukuran file lebih kecil.

### 1.3. Buat File ZIP

Masuk ke folder project `ARTIKA_THEME`, lalu **buat file .zip** yang berisi **seluruh isi folder** dengan pengecualian berikut:

**❌ JANGAN masukkan ke dalam ZIP:**

| Folder/File             | Alasan                                         |
| ----------------------- | ---------------------------------------------- |
| `node_modules/`         | Sangat berat (100MB+), tidak diperlukan server |
| `.git/`                 | Riwayat Git, tidak diperlukan server           |
| `tests/`                | Folder testing lokal                           |
| `storage/logs/*.log`    | File log lokal, bukan untuk server             |
| `.phpunit.result.cache` | Cache testing                                  |

**✅ WAJIB masukkan ke dalam ZIP:**

| Folder/File            | Alasan                         |
| ---------------------- | ------------------------------ |
| `vendor/`              | Semua library PHP (wajib!)     |
| `public/build/`        | Hasil build CSS/JS (wajib!)    |
| `database/migrations/` | Struktur tabel database        |
| `database/seeders/`    | Data awal sistem (role, admin) |
| `.env.example`         | Template konfigurasi           |
| Seluruh file lainnya   | Framework Laravel              |

---

## 🖥️ TAHAP 2: Upload File ke Server via WinSCP

### 2.1. Koneksi WinSCP

1. Buka **WinSCP**.
2. Isi kolom koneksi:
    - **File Protocol**: SFTP (atau SCP)
    - **Host name**: IP server / `artika.smkn1ciamis.id`
    - **Port number**: `22`
    - **User name**: Username SSH dari IT sekolah
    - **Password**: Password SSH dari IT sekolah
3. Klik **Login**.

### 2.2. Upload File ZIP

1. Di sisi server (panel kanan), navigasi ke folder home, misalnya `/home/username/` atau `/var/www/`.
2. Upload file `.zip` yang sudah disiapkan tadi ke server.

---

## ⚙️ TAHAP 3: Setup di Server via SSH (Terminal)

Buka koneksi SSH (bisa melalui WinSCP: **Commands → Open Terminal** atau pakai aplikasi seperti **PuTTY**).

### 3.1. Extract File ZIP

```bash
cd /var/www
sudo unzip ARTIKA_THEME.zip -d artika
cd artika
```

> Sesuaikan path `/var/www` dengan konfigurasi server Anda.

### 3.2. Set Permission Folder

```bash
sudo chown -R www-data:www-data /var/www/artika
sudo chmod -R 755 /var/www/artika
sudo chmod -R 775 /var/www/artika/storage
sudo chmod -R 775 /var/www/artika/bootstrap/cache
```

> `www-data` adalah user default Nginx/Apache di Ubuntu. Ganti jika server menggunakan user lain (cek: `ps aux | grep nginx`).

### 3.3. Konfigurasi Environment (.env)

```bash
cp .env.example .env
nano .env
```

Edit nilai-nilai berikut sesuai **data server Anda**:

```env
APP_NAME="ARTIKA POS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://artika.smkn1ciamis.id

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=artika_pos_db
DB_USERNAME=artika_user
DB_PASSWORD=PASSWORD_KUAT_ANDA

SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_DOMAIN=artika.smkn1ciamis.id

CACHE_STORE=database
QUEUE_CONNECTION=database
```

> **⚠️ KRITIS**: Ganti `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai akun database yang dibuat di tahap berikutnya!

Simpan file: `Ctrl+O` → `Enter` → `Ctrl+X`.

### 3.4. Generate App Key

```bash
php artisan key:generate
```

---

## 🗄️ TAHAP 4: Setup Database (PostgreSQL)

### 4.1. Buat Database & User

Login ke PostgreSQL:

```bash
sudo -u postgres psql
```

Jalankan perintah SQL berikut:

```sql
CREATE DATABASE artika_pos_db;
CREATE USER artika_user WITH ENCRYPTED PASSWORD 'PASSWORD_KUAT_ANDA';
GRANT ALL PRIVILEGES ON DATABASE artika_pos_db TO artika_user;
ALTER DATABASE artika_pos_db OWNER TO artika_user;
\q
```

> Ganti `PASSWORD_KUAT_ANDA` dengan password yang kuat dan catat baik-baik.

### 4.2. Jalankan Migrasi & Seeder

```bash
php artisan migrate --force
php artisan db:seed --class=RoleAndSystemSeeder --force
```

> **Penting:** Gunakan `RoleAndSystemSeeder`, BUKAN `db:seed` biasa. `RoleAndSystemSeeder` hanya mengisi data sistem esensial (role, superadmin, payment methods). Seeder default `DatabaseSeeder` berisi data demo/contoh yang **tidak boleh** ada di production.

### 4.3. Buat Storage Link

```bash
php artisan storage:link
```

Ini akan membuat symlink `public/storage → storage/app/public` agar file upload (gambar produk, bukti bayar) bisa diakses dari web.

---

## 🌐 TAHAP 5: Konfigurasi Web Server (Nginx)

### 5.1. Buat File Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/artika
```

Isi dengan konfigurasi berikut:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name artika.smkn1ciamis.id;

    root /var/www/artika/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 10M;

    # Redirect semua HTTP ke HTTPS (aktifkan setelah SSL dipasang)
    # return 301 https://$server_name$request_uri;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

> **Sesuaikan**:
>
> - `php8.3-fpm.sock` → sesuaikan dengan versi PHP di server Anda (cek: `ls /run/php/`).
> - `client_max_body_size 10M` → batas upload file (cukup untuk Excel dan gambar).

### 5.2. Aktifkan Site & Restart Nginx

```bash
sudo ln -s /etc/nginx/sites-available/artika /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

> `nginx -t` akan mengecek apakah konfigurasi valid. Jika ada error, perbaiki dulu sebelum restart.

---

## 🔒 TAHAP 6: Pasang SSL (HTTPS) dengan Certbot

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d artika.smkn1ciamis.id
```

Ikuti instruksi di layar. Certbot akan otomatis:

- Mengambil sertifikat SSL gratis dari Let's Encrypt.
- Mengonfigurasi Nginx untuk HTTPS.
- Mengaktifkan redirect otomatis HTTP → HTTPS.

> **Perpanjangan Otomatis**: Sertifikat berlaku 90 hari. Certbot sudah mengatur cron job untuk auto-renew. Cek: `sudo certbot renew --dry-run`.

---

## 🔥 TAHAP 7: Optimasi Produksi (Laravel Cache)

Setelah semua langkah selesai, jalankan perintah optimasi:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

Ini akan mempercepat waktu loading aplikasi secara signifikan di production.

---

## ✅ TAHAP 8: Checklist Final & Verifikasi

Jalankan pengecekan ini satu per satu:

### 8.1. Cek Akses Web

Buka browser dan akses: `https://artika.smkn1ciamis.id`

Anda seharusnya melihat halaman login ARTIKA POS.

### 8.2. Cek Login

Login dengan akun **Superadmin** default yang dibuat oleh seeder:

- **Username**: `superadmin`
- **Password**: `password` (Sesuai yang di-set di `RoleAndSystemSeeder`)

> **⚠️ KRITIS**: Segera ganti password Superadmin setelah login pertama kali melalui menu **Pengaturan Profil**!

### 8.3. Checklist Pasca-Deploy

- [ ] **HTTPS aktif** (ada ikon gembok di address bar browser).
- [ ] **Login berhasil** sebagai Superadmin.
- [ ] **Password Superadmin sudah diganti** dari default.
- [ ] **Storage link aktif** (gambar produk bisa tampil).
- [ ] **POS Kasir berfungsi** (scan barcode, checkout, cetak struk).
- [ ] **Import Excel berfungsi** (produk, user, supplier).
- [ ] **Printer struk terhubung** (jika menggunakan printer thermal).

---

## 🛠️ Troubleshooting Umum

### Halaman Blank / Error 500

```bash
# Cek log error Laravel
cat /var/www/artika/storage/logs/laravel.log | tail -50

# Pastikan permission benar
sudo chmod -R 775 /var/www/artika/storage
sudo chmod -R 775 /var/www/artika/bootstrap/cache
sudo chown -R www-data:www-data /var/www/artika
```

### CSS/JS Tidak Muncul (Tampilan Berantakan)

```bash
# Pastikan folder build ada
ls /var/www/artika/public/build/
# Harus ada: manifest.json dan folder assets/

# Jika tidak ada, build ulang di server:
npm install
npm run build
```

### Database Error (Connection Refused)

```bash
# Cek PostgreSQL berjalan
sudo systemctl status postgresql

# Cek konfigurasi .env
cat /var/www/artika/.env | grep DB_

# Test koneksi manual
psql -U artika_user -d artika_pos_db -h 127.0.0.1
```

### Upload File Gagal (413 Request Entity Too Large)

```bash
# Tambahkan di konfigurasi Nginx:
# client_max_body_size 10M;
sudo nano /etc/nginx/sites-available/artika
sudo systemctl restart nginx
```

### Gambar Produk Tidak Muncul

```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Pastikan folder uploads punya permission yang benar
sudo chmod -R 775 /var/www/artika/public/uploads
```

---

## 📝 Ringkasan Perintah (Quick Reference)

Untuk kemudahan, berikut seluruh perintah SSH yang dijalankan secara berurutan:

```bash
# === SETUP AWAL ===
cd /var/www
sudo unzip ARTIKA_THEME.zip -d artika
cd artika

# === PERMISSION ===
sudo chown -R www-data:www-data /var/www/artika
sudo chmod -R 755 /var/www/artika
sudo chmod -R 775 /var/www/artika/storage
sudo chmod -R 775 /var/www/artika/bootstrap/cache

# === ENVIRONMENT ===
cp .env.example .env
nano .env                          # Edit DB credentials & APP_URL
php artisan key:generate

# === DATABASE (PostgreSQL) ===
sudo -u postgres psql
# CREATE DATABASE artika_pos_db;
# CREATE USER artika_user WITH ENCRYPTED PASSWORD 'PASSWORD_ANDA';
# GRANT ALL PRIVILEGES ON DATABASE artika_pos_db TO artika_user;
# ALTER DATABASE artika_pos_db OWNER TO artika_user;
# \q

php artisan migrate --force
php artisan db:seed --class=RoleAndSystemSeeder --force
php artisan storage:link

# === NGINX ===
sudo nano /etc/nginx/sites-available/artika
sudo ln -s /etc/nginx/sites-available/artika /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# === SSL ===
sudo certbot --nginx -d artika.smkn1ciamis.id

# === OPTIMASI ===
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

**Dibuat oleh © Tim RPL Sentinel 2026**  
**Terakhir Diperbarui:** 2026-03-03
