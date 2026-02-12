# 🚀 Panduan Deployment ARTIKA POS (FTP/Shared Hosting)

Gunakan panduan ini untuk memindahkan ARTIKA POS ke server sekolah atau hosting berbasis cPanel/VPS menggunakan FTP, SSH, atau WinSCP.

---

## 🛠️ Checklist Persiapan Deployment (Wajib!)

Sebelum membuka WinSCP, pastikan poin-poin berikut sudah siap agar tidak terjadi error saat online:

### 1. Persiapan Local (Komputer Anda)

- [ ] **Build Frontend**: Jalankan `npm run build` untuk menghasilkan file CSS/JS produksi.
- [ ] **Clean Up Folder**: Hapus folder `node_modules` (ini sangat berat dan tidak boleh diunggah).
- [ ] **Optimize Laravel**: Jalankan `php artisan optimize:clear` untuk membersihkan cache lokal.
- [ ] **Dependency**: Jalankan `composer install --no-dev --optimize-autoloader` agar hanya library inti yang terunggah.
- [ ] **Migrations**: **Wajib di-upload** jika Anda ingin menggunakan `php artisan migrate` di server. File ini sangat ringan (total < 1MB) dan **tidak akan memenuhi database**. Database hanya penuh jika data transaksi sudah ribuan/jutaan.

### 2. Folder & File yang HARUS Dihapus/Dikecualikan (Exclusion List)

Daftar ini sebaiknya **TIDAK DI-UPLOAD** via WinSCP untuk menghemat waktu dan ruang disk server:

- `node_modules/` (Gunakan `npm run build` di lokal saja).
- `.git/` (Sangat berat, isinya riwayat commit Anda).
- `tests/` (Hanya untuk testing di lokal).
- `storage/logs/*.log` (Hapus log lama di lokal).
- `storage/framework/views/*.php` (File cache view).
- `artika_backup_*.sql` (File backup database lokal Anda).
- `.env.example`, `phpunit.xml`, `.editorconfig`.

### 3. Informasi Server (Wajib Tanya IT Perusahaan)

- [ ] **Akses WinSCP**: Alamat Host (IP/Domain), Port (biasanya 21 untuk FTP atau 22 untuk SFTP), Username, dan Password.
- [ ] **Versi PHP Server**: Pastikan minimal **PHP 8.2**.
- [ ] **Akses Database**: Nama Database, Username DB, dan Password DB yang sudah dibuat di server.
- [ ] **Path Root**: Di folder mana file harus ditaruh (misal: `/var/www/html` atau `/public_html`).

### 3. File Konfigurasi

- [ ] **.env Server**: Siapkan satu file `.env` khusus untuk server (matikan DEBUG, atur URL, atur koneksi DB server).

---

## 📋 Langkah 1: Persiapan File (Lokal)

Sebelum mengunggah, jalankan perintah ini di komputer Anda untuk mengoptimalkan file:

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📂 Langkah 2: Struktur FTP yang Aman

Untuk keamanan maksimal di Shared Hosting, **JANGAN** letakkan semua file di dalam `public_html`. Gunakan struktur ini:

1. **Folder `core_artika`** (Di luar `public_html`): Unggah seluruh file Laravel KECUALI isi folder `public`.
2. **Folder `public_html`**: Unggah hanya isi dari folder `public` Laravel (index.php, css, js, images).

### Konfigurasi `index.php`

Edit file `public_html/index.php` agar mengarah ke folder core:

```php
require __DIR__.'/../core_artika/vendor/autoload.php';
$app = require_once __DIR__.'/../core_artika/bootstrap/app.php';
```

---

## 🗄️ Langkah 3: Database (PHPMyAdmin)

1. Export database lokal Anda ke file `.sql`.
2. Login ke cPanel/Panel Server Sekolah, buka **PHPMyAdmin**.
3. Buat database baru, lalu klik **Import** dan pilih file `.sql` tadi.
4. Buat **Database User** dan hubungkan ke database dengan hak akses penuh.

---

## ⚙️ Langkah 4: Konfigurasi Environment (`.env`)

Edit file `.env` di dalam folder `core_artika` di server:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://nama-sekolah.sch.id`
- Masukkan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai langkah 3.

---

## � Langkah 5: Deployment via SSH (VPS / Linux Server)

Jika Anda memiliki akses SSH (Terminal), ini adalah cara yang lebih modern dan aman:

### 1. Hubungkan ke Server

```bash
ssh username@ip-server-sekolah
cd /var/www/artika
```

### 2. Gunakan Git untuk Update

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. Setup Database (Command Line)

Jika belum ada database:

```bash
mysql -u root -p -e "CREATE DATABASE artika_pos;"
php artisan migrate --seed
```

### 4. Nginx Configuration

Pastikan root folder Nginx mengarah ke folder `/public`:

```nginx
server {
    listen 80;
    root /var/www/artika/public;
    index index.php index.html;
    ...
}
```

---

## �🛡️ Checklist Pasca Deployment

- [ ] **HTTPS**: Pastikan domain menggunakan SSL (Gembok Hijau).
- [ ] **Folder Permission**: Folder `storage` and `bootstrap/cache` harus bisa ditulis (**Writable / CHMOD 775**).
- [ ] **Storage Link**: Jalankan `php artisan storage:link`.

---

**Tim Arsitek ARTIKA POS**
