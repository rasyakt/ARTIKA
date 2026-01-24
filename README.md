# 🛒 ARTIKA POS System

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap)

**ARTIKA** adalah sistem Point of Sale (POS) lengkap dan modern yang dibangun dengan Laravel 12, dirancang khusus untuk retail dan toko dengan fitur-fitur canggih seperti barcode scanning, role-based access control, dan inventory management.

---

## ✨ Fitur Utama

### 🎯 Point of Sale (POS)

- **Smart Barcode Scanner** - Anti-spam cooldown (2.5s) & audio-visual feedback
- **Product Search** - Real-time search by name, barcode, or category
- **Cart Management** - Mobile-optimized carts with quantity controls
- **Unified Payment Flow** - Cash & Non-Cash methods with file upload proof
- **Hold Transaction** - Park/resume transactions easily
- **Keyboard Shortcuts** - Workflow cepat dengan shortcut (F2, F4, F8, Esc)
- **Auto Calculation** - Perhitungan otomatis subtotal, diskon, pajak, kembalian
- **Receipt Printing** - Cetak struk transaksi

### 👨‍💼 Admin Dashboard

- **Product Management** - CRUD produk dengan barcode
- **Category Management** - Kelola kategori produk
- **User Management** - Kelola user dengan role-based access
- **Customer Management** - Database pelanggan dengan loyalty points
- **Sales Reports** - Laporan penjualan dan analitik

### 📦 Warehouse Management

- **Stock Management** - Monitoring dan kelola stok produk
- **Low Stock Alerts** - Notifikasi stok menipis
- **Stock Movements** - Tracking pergerakan stok detail (in/out)
- **Advanced Reports** - PDF/CSV export for Warehouse, Cashier, and Audit logs
- **Stock Adjustment** - Penyesuaian stok manual dengan pencatatan log

### 🔐 Authentication & Security

- **Dual Login System** - Login dengan Username atau NIS (untuk kasir/siswa)
- **Role-Based Access Control** - 3 role: Admin, Cashier, Warehouse
- **Secure Password** - Bcrypt password hashing
- **Session Management** - Secure session handling

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 5.7+
- Node.js & NPM (untuk asset compilation)
- Laragon / XAMPP / Valet (opsional, untuk development lokal)

### Installation

```bash
# Clone repository
git clone https://github.com/yourusername/artika-pos.git
cd artika-pos

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika
DB_USERNAME=root
DB_PASSWORD=

# Run migrations & seeders
php artisan migrate:fresh --seed

# Compile assets
npm run build

# Start development server
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

### Default Login Credentials

| Role          | Username | NIS     | Password   | Dashboard              |
| ------------- | -------- | ------- | ---------- | ---------------------- |
| **Admin**     | `admin`  | -       | `password` | `/admin/dashboard`     |
| **Cashier**   | `kasir1` | `12345` | `password` | `/pos`                 |
| **Warehouse** | `gudang` | -       | `password` | `/warehouse/dashboard` |

> **Note:** Cashier dapat login dengan Username (`kasir1`) atau NIS (`12345`)

---

## 📚 Documentation

Dokumentasi lengkap tersedia dalam file-file berikut:

### 🔧 Setup & Installation

- **[INSTALLATION.md](file:///c:/laragon/www/ARTIKA/INSTALLATION.md)** - Panduan instalasi lengkap untuk Windows, Linux, dan macOS
- **[DEPLOYMENT.md](file:///c:/laragon/www/ARTIKA/DEPLOYMENT.md)** - Panduan deployment ke production server

### 🏗️ Technical Documentation

- **[ARCHITECTURE.md](file:///c:/laragon/www/ARTIKA/ARCHITECTURE.md)** - Arsitektur sistem dan design patterns
- **[DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md)** - Database schema dan Entity Relationship Diagram
- **[API.md](file:///c:/laragon/www/ARTIKA/API.md)** - API routes dan endpoint documentation

### 📖 User Guides

- **[Admin Guide](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_ADMIN.md)** - Panduan lengkap untuk Admin
- **[Cashier Guide](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_CASHIER.md)** - Panduan lengkap untuk Kasir/Cashier
- **[Warehouse Guide](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_WAREHOUSE.md)** - Panduan lengkap untuk Staff Gudang

### 👨‍💻 Developer Documentation

- **[DEVELOPMENT.md](file:///c:/laragon/www/ARTIKA/DEVELOPMENT.md)** - Development environment setup dan coding standards
- **[CONTRIBUTING.md](file:///c:/laragon/www/ARTIKA/CONTRIBUTING.md)** - Panduan kontribusi untuk developer

### 📋 Additional Resources

- **[CHANGELOG.md](file:///c:/laragon/www/ARTIKA/CHANGELOG.md)** - Version history dan release notes
- **[FAQ.md](file:///c:/laragon/www/ARTIKA/FAQ.md)** - Frequently Asked Questions dan troubleshooting

---

## 🎨 Design System

ARTIKA menggunakan **Modern Brown Theme** yang elegan dan profesional:

- **Primary Color:** `#85695a` (Warm Brown)
- **Accent Color:** `#c17a5c` (Terracotta)
- **Background:** Light cream tones (`#fdf8f6`, `#f2e8e5`)
- **Typography:** Inter font family (Google Fonts)
- **UI Style:** Modern card-based design with glassmorphism effects
- **Responsive:** Fully responsive untuk desktop, tablet, dan mobile

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade Templates + Bootstrap 5 + Custom SCSS
- **Database:** MySQL with Foreign Keys
- **Authentication:** Custom multi-field authentication (Username/NIS)
- **Architecture:** MVC with Service & Repository Pattern
- **Asset Bundling:** Vite
- **Barcode Scanner:** html5-qrcode library
- **Icons:** Bootstrap Icons

---

## 📊 Database Overview

Sistem menggunakan 19 tabel utama:

- `users` - User accounts dengan role-based access
- `roles` - User roles (Admin, Cashier, Warehouse)
- `categories` - Product categories
- `products` - Product catalog dengan barcode
- `stocks` - Inventory
- `customers` - Customer database dengan loyalty points
- `transactions` - Sales transactions
- `transaction_items` - Transaction line items
- `held_transactions` - Parked/held transactions
- `returns` - Return/refund records
- `payment_methods` - Payment options
- `shifts` - Cashier shift management
- `journals` - Accounting journal entries
- `promos` - Promotions dan discounts
- `stock_movements` - Stock movement tracking
- Dan lainnya...

Lihat [DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md) untuk detail lengkap schema dan ERD.

---

## ⌨️ Keyboard Shortcuts (POS)

| Shortcut | Action                   |
| -------- | ------------------------ |
| `F2`     | Open checkout modal      |
| `F4`     | Hold current transaction |
| `F8`     | Clear cart               |
| `Esc`    | Cancel/close modal       |

---

## 🔄 Development Workflow

```bash
# Install dependencies
composer install && npm install

# Run migrations & seed database
php artisan migrate:fresh --seed

# Start development servers
npm run dev          # Vite dev server (terminal 1)
php artisan serve    # Laravel dev server (terminal 2)
```

Lihat [DEVELOPMENT.md](file:///c:/laragon/www/ARTIKA/DEVELOPMENT.md) untuk panduan development lengkap.

---

## 📝 License

This project is licensed under the MIT License.

---

## 🤝 Contributing

Kontribusi selalu welcome! Silakan baca [CONTRIBUTING.md](file:///c:/laragon/www/ARTIKA/CONTRIBUTING.md) untuk panduan berkontribusi.

---

## 📧 Support

Jika menemukan bug atau memiliki pertanyaan, silakan buat issue di repository ini atau hubungi tim development.

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap Team
- html5-qrcode Library
- Dan semua open-source libraries yang digunakan dalam project ini

---

**Version:** 2.5  
**Last Updated:** 2026-01-23  
**Status:** ✅ Production Ready & Mobile Optimized

---

Made with Laravel & Bootstrap
