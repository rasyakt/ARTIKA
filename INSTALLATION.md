# 📦 ARTIKA POS - Installation Guide

Complete installation guide untuk ARTIKA POS System pada berbagai platform (Windows, Linux, macOS).

---

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Installation - Windows (Laragon)](#installation---windows-laragon)
- [Installation - Windows (XAMPP)](#installation---windows-xampp)
- [Installation - Linux](#installation---linux)
- [Installation - macOS](#installation---macos)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Asset Compilation](#asset-compilation)
- [Running the Application](#running-the-application)
- [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements

- **PHP:** 8.2 atau lebih tinggi
- **Composer:** 2.x
- **Node.js:** 18.x atau lebih tinggi
- **NPM:** 9.x atau lebih tinggi
- **MySQL:** 5.7+ atau MariaDB 10.3+
- **Web Server:** Apache 2.4+ atau Nginx 1.18+
- **Memory:** 512MB minimum, 1GB recommended
- **Disk Space:** 500MB minimum

### PHP Extensions Required

Pastikan PHP extensions berikut sudah terinstall dan enabled:

```
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD (untuk image processing)
```

Cek PHP extensions dengan command:
```bash
php -m
```

---

## Installation - Windows (Laragon)

### 1. Install Laragon

Download dan install **Laragon** dari [laragon.org](https://laragon.org/download/)

Pilih **Laragon Full** yang sudah include Apache, PHP 8.x, MySQL, dan Node.js.

### 2. Setup Project

```bash
# Navigate ke document root Laragon
cd C:\laragon\www

# Clone atau copy project ARTIKA
# Jika dari git:
git clone https://github.com/yourusername/artika-pos.git ARTIKA

# Atau copy folder project ke C:\laragon\www\ARTIKA

# Masuk ke folder project
cd ARTIKA
```

### 3. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 4. Environment Configuration

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME="ARTIKA POS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://artika.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database

Buka **HeidiSQL** (included in Laragon) atau MySQL client lainnya:

```sql
CREATE DATABASE artika CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run Migrations & Seeders

```bash
# Run migrations dan seeders
php artisan migrate:fresh --seed
```

Perintah ini akan:
- Membuat semua tabel database
- Mengisi data sample (users, products, categories, dll)

### 7. Setup Virtual Host (Optional)

Laragon biasanya otomatis membuat virtual host. Akses aplikasi via:
- `http://artika.test` (jika Laragon auto-detect)
- atau `http://localhost/ARTIKA/public`

Untuk setup manual virtual host, klik kanan Laragon icon → Apache → `sites-enabled` → buat file `artika.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/laragon/www/ARTIKA/public"
    ServerName artika.test
    <Directory "C:/laragon/www/ARTIKA/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Tambahkan di `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 artika.test
```

### 8. Compile Assets

```bash
# Development mode (with hot reload)
npm run dev

# Atau build untuk production
npm run build
```

### 9. Start Application

Jika menggunakan Laragon virtual host:
```
http://artika.test
```

Atau gunakan PHP built-in server:
```bash
php artisan serve
# Akses di http://localhost:8000
```

---

## Installation - Windows (XAMPP)

### 1. Install XAMPP

Download dan install **XAMPP** dari [apachefriends.org](https://www.apachefriends.org/)

Pilih versi dengan PHP 8.2+

### 2. Install Composer

Download dan install **Composer** dari [getcomposer.org](https://getcomposer.org/download/)

### 3. Install Node.js

Download dan install **Node.js** dari [nodejs.org](https://nodejs.org/)

Pilih versi LTS (Long Term Support)

### 4. Setup Project

```bash
# Navigate ke htdocs XAMPP
cd C:\xampp\htdocs

# Clone atau copy project
git clone https://github.com/yourusername/artika-pos.git ARTIKA
cd ARTIKA
```

### 5. Install Dependencies

```bash
composer install
npm install
```

### 6. Environment Configuration

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Create Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`):
- Buat database baru bernama `artika`
- Collation: `utf8mb4_unicode_ci`

### 8. Run Migrations

```bash
php artisan migrate:fresh --seed
```

### 9. Compile Assets & Run

```bash
# Terminal 1: Compile assets
npm run dev

# Terminal 2: Start server (optional, bisa langsung via Apache)
php artisan serve
```

Akses aplikasi di:
- `http://localhost/ARTIKA/public`
- atau `http://localhost:8000` (jika pakai artisan serve)

---

## Installation - Linux (Ubuntu/Debian)

### 1. Update System

```bash
sudo apt update
sudo apt upgrade
```

### 2. Install PHP 8.2+

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Install PHP dan extensions
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql \
php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
php8.2-bcmath php8.2-intl
```

### 3. Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 4. Install Node.js & NPM

```bash
# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

### 5. Install MySQL

```bash
sudo apt install mysql-server
sudo mysql_secure_installation
```

### 6. Setup Project

```bash
# Navigate to web directory
cd /var/www

# Clone project
sudo git clone https://github.com/yourusername/artika-pos.git artika
cd artika

# Set permissions
sudo chown -R www-data:www-data /var/www/artika
sudo chmod -R 755 /var/www/artika
sudo chmod -R 775 /var/www/artika/storage
sudo chmod -R 775 /var/www/artika/bootstrap/cache
```

### 7. Install Dependencies

```bash
composer install --no-dev
npm install
```

### 8. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=artika
DB_USERNAME=artika_user
DB_PASSWORD=your_password
```

### 9. Create Database & User

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE artika CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'artika_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON artika.* TO 'artika_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 10. Run Migrations

```bash
php artisan migrate:fresh --seed
```

### 11. Setup Apache Virtual Host

```bash
sudo nano /etc/apache2/sites-available/artika.conf
```

Tambahkan:
```apache
<VirtualHost *:80>
    ServerName artika.local
    ServerAdmin admin@artika.local
    DocumentRoot /var/www/artika/public

    <Directory /var/www/artika/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/artika-error.log
    CustomLog ${APACHE_LOG_DIR}/artika-access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite artika.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 12. Build Assets

```bash
npm run build
```

---

## Installation - macOS

### 1. Install Homebrew

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### 2. Install PHP

```bash
brew install php@8.2
brew link php@8.2
```

### 3. Install Composer

```bash
brew install composer
```

### 4. Install Node.js

```bash
brew install node@18
```

### 5. Install MySQL

```bash
brew install mysql
brew services start mysql
mysql_secure_installation
```

### 6. Setup Project

```bash
cd ~/Sites  # atau directory lain
git clone https://github.com/yourusername/artika-pos.git artika
cd artika
```

### 7. Install Dependencies

```bash
composer install
npm install
```

### 8. Environment & Database

```bash
cp .env.example .env
php artisan key:generate
```

Create database:
```bash
mysql -u root -p
```
```sql
CREATE DATABASE artika CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 9. Run Migrations

```bash
php artisan migrate:fresh --seed
```

### 10. Run Application

```bash
# Terminal 1
npm run dev

# Terminal 2
php artisan serve
```

---

## Environment Configuration

### Full `.env` Configuration Example

```env
# Application
APP_NAME="ARTIKA POS"
APP_ENV=local
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@artika.test"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Database Setup

### Default Seeded Data

Setelah menjalankan `php artisan migrate:fresh --seed`, database akan berisi:

**Users (3)**
- Admin: username `admin`, password `password`
- Kasir: username `kasir1`, NIS `12345`, password `password`
- Gudang: username `gudang`, password `password`

**Branches (2)**
- Pusat
- Cabang 1

**Categories (5)**
- Snack, Drink, Food, Dairy, Household

**Products (20)**
- Various products dengan barcode unik
- Stock 50-200 per product

**Customers (3)**
- Sample customer data dengan loyalty points

**Payment Methods (5)**
- Cash, QRIS, Debit Card, Credit Card, E-Wallet

---

## Asset Compilation

### Development Mode

```bash
npm run dev
```
- Hot module replacement (HMR)
- Source maps
- Tidak minified

### Production Build

```bash
npm run build
```
- Minified & optimized
- Versioned assets
- Ready for deployment

---

## Running the Application

### Option 1: PHP Built-in Server (Development)

```bash
php artisan serve
```
Akses: `http://localhost:8000`

### Option 2: Laragon/XAMPP (Development)

```
http://artika.test
http://localhost/ARTIKA/public
```

### Option 3: Production Server

Setup web server (Apache/Nginx) dengan document root pointing ke `/public` directory.

Lihat [DEPLOYMENT.md](file:///c:/laragon/www/ARTIKA/DEPLOYMENT.md) untuk production deployment.

---

## Troubleshooting

### Error: "No application encryption key has been specified"

**Solution:**
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Solution:**
- Check database credentials di `.env`
- Pastikan MySQL service running
- Test connection: `mysql -u root -p`

### Error: "Class 'PDO' not found"

**Solution:**
Enable PHP PDO extension:
```bash
# Windows: uncomment di php.ini
extension=pdo_mysql

# Linux:
sudo apt install php8.2-mysql
```

### Error: "Permission denied" (Linux)

**Solution:**
```bash
sudo chown -R www-data:www-data /var/www/artika
sudo chmod -R 775 storage bootstrap/cache
```

### Error: npm install fails

**Solution:**
```bash
# Clear cache
npm cache clean --force

# Delete node_modules dan reinstall
rm -rf node_modules package-lock.json
npm install
```

### Error: "Mix manifest does not exist"

**Solution:**
```bash
npm run build
```

### Database migration error

**Solution:**
```bash
# Drop all tables dan migrate ulang
php artisan migrate:fresh --seed
```

### Port 8000 already in use

**Solution:**
```bash
# Gunakan port lain
php artisan serve --port=8080
```

### Vite server tidak bisa diakses

**Solution:**
```bash
# Edit vite.config.js, tambahkan server config:
server: {
    host: '0.0.0.0',
    port: 5173
}
```

---

## Post-Installation

### 1. Verify Installation

Buka browser dan akses aplikasi. Test login dengan credentials default.

### 2. Change Default Passwords

**PENTING:** Ubah password default setelah installation!

Login sebagai admin dan ubah semua user passwords di User Management.

### 3. Configure Application

- Setup branch/cabang toko
- Tambahkan kategori dan produk
- Konfigurasi payment methods sesuai kebutuhan
- Setup printer untuk receipt (opsional)

### 4. Backup Database

```bash
# Manual backup
mysqldump -u root -p artika > artika_backup.sql

# Restore backup
mysql -u root -p artika < artika_backup.sql
```

---

## Next Steps

- 📖 Baca [USER_GUIDE_ADMIN.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_ADMIN.md) untuk panduan Admin
- 📖 Baca [USER_GUIDE_CASHIER.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_CASHIER.md) untuk panduan Kasir
- 📖 Baca [USER_GUIDE_WAREHOUSE.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_WAREHOUSE.md) untuk panduan Gudang
- 🔧 Baca [DEVELOPMENT.md](file:///c:/laragon/www/ARTIKA/DEVELOPMENT.md) untuk development
- 🚀 Baca [DEPLOYMENT.md](file:///c:/laragon/www/ARTIKA/DEPLOYMENT.md) untuk production deployment

---

## Support

Jika mengalami masalah installation, silakan:
1. Check [FAQ.md](file:///c:/laragon/www/ARTIKA/FAQ.md)
2. Buat issue di GitHub repository
3. Hubungi tim development

---

**Happy Installing! 🎉**
