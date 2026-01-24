# 📝 ARTIKA POS - Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.5.0] - 2026-01-23

### ✨ Branding & Mobile Optimization

### Added

- 🚀 **Unified Brand Identity**
    - Replaced generic "ARTIKA POS" text with corporate `logo2.png` logo across all main dashboards.
    - Standardized logo placement in Admin, Warehouse, and POS interface.
    - Custom logo exception for Login Page (using white `logo.png` version for better contrast).
- 📱 **Mobile Navigation Enhancement**
    - New "Back to POS" navigation button in Transaction History for mobile users.
    - Smart-condensing buttons (icon-only or short labels) on small screens.
- 🔊 **Smart Barcode Scanner Feedback**
    - Audio feedback: "Scanner Beep" sound (Web Audio API) upon successful detection.
    - Visual feedback: Instant SweetAlert2 toast notification with product name.
    - Prevention logic: 2.5-second cooldown for the same barcode to prevent "spamming" items.
- 🌐 **Ngrok Connectivity Support**
    - `TrustProxies` configuration to handle ngrok secure headers.
    - Automatic HTTPS enforcement in `AppServiceProvider` for `*.ngrok-free.dev` domains.

### Changed

- 🔄 Updated `AppServiceProvider` to force HTTPS scheme when behind a proxy.
- 🔄 Modified `bootstrap/app.php` to trust all proxies for public accessibility.

### [2.0.0] - 2026-01-09

### 🎉 Major Release - Production Ready

### Added

#### Core Features

- ✨ **Point of Sale (POS) System**
    - Modern POS interface with product grid and shopping cart
    - Real-time product search by name or barcode
    - Shopping cart with quantity adjustment (+/- buttons)
    - Multiple payment methods (Cash, QRIS, Debit, Credit, E-Wallet)
    - Automatic calculation of subtotal, discount, tax, and change
    - Receipt generation and printing support
- ✨ **Barcode Scanning**
    - USB barcode scanner support (plug & play)
    - Camera barcode scanner using html5-qrcode library
    - Manual barcode entry option
    - Scanner toggle (show/hide camera scanner)

- ✨ **Transaction Management**
    - Hold transaction feature (F4) - save cart for later
    - Resume held transactions
    - Transaction history logging
    - Auto-generated invoice numbers (INV-YYYYMMDD-XXXX)
    - Stock auto-deduction on completed sale

- ✨ **Keyboard Shortcuts**
    - F2: Open checkout modal
    - F4: Hold current transaction
    - F8: Clear cart
    - Esc: Cancel/close modal

#### Admin Dashboard

- ✨ **Product Management**
    - CRUD operations for products
    - Barcode support (unique per product)
    - Category assignment
    - Price and cost price tracking
    - Ellipsis dropdown menu for actions (Edit/Delete)

- ✨ **Category Management**
    - Create, edit, delete categories
    - URL-friendly slugs
    - Product count per category

- ✨ **User Management**
    - Full user CRUD
    - Role-based access control (Admin, Cashier, Warehouse)
    - Multi-field authentication (Username or NIS)
    - Branch assignment per user
    - Ellipsis dropdown menu for user actions

- ✨ **Customer Management**
    - Customer database with contact information
    - Loyalty points system
    - Member since tracking
    - Purchase history integration

#### Warehouse Management

- ✨ **Stock Management**
    - Real-time stock tracking per branch
    - Low stock alerts (quantity ≤ min_stock)
    - Stock adjustment feature
    - Multi-branch stock support

- ✨ **Stock Movements**
    - Complete audit trail of stock changes
    - Movement types: IN, OUT, ADJUSTMENT, TRANSFER, SALE
    - Quantity before/after tracking
    - User attribution for changes
    - Reference number support
    - Detailed notes for adjustments

#### Design & UX

- 🎨 **Modern Brown Theme**
    - Professional warm brown color scheme
    - Primary: #85695a, Accent: #c17a5c
    - Light cream backgrounds (#fdf8f6, #f2e8e5)
    - Inter font family (Google Fonts)
    - Glassmorphism effects
    - Smooth animations and transitions
    - Fully responsive design (mobile, tablet, desktop)

- 🎨 **UI Components**
    - Card-based product grid
    - Modern sidebar navigation
    - Responsive hamburger menu for mobile
    - Modal dialogs for forms
    - Toast notifications
    - Loading states

#### Authentication & Security

- 🔐 **Dual Login System**
    - Login with Username (e.g., `admin`, `kasir1`)
    - Login with NIS for cashiers/students (e.g., `12345`)
    - Auto-detection of login field type
    - Bcrypt password hashing

- 🔐 **Role-Based Access Control**
    - RoleMiddleware for route protection
    - Admin: Full system access
    - Cashier: POS interface only
    - Warehouse: Stock management only
    - Automatic dashboard redirection per role

#### Database

- 💾 **Complete Schema (19 Tables)**
    - Core: users, roles, branches
    - Products: products, categories, stocks
    - Sales: transactions, transaction_items, payment_methods
    - Customers: customers, held_transactions, returns
    - Warehouse: stock_movements
    - Accounting: journals
    - Other: shifts, promos
    - System: sessions, cache, jobs

- 💾 **Relationships & Constraints**
    - Foreign keys with cascading deletes
    - Unique constraints (barcode, username, NIS)
    - Indexes for performance

- 💾 **Database Seeder**
    - 3 Default users (Admin, Cashier, Warehouse)
    - 2 Branches (Pusat, Cabang 1)
    - 5 Categories
    - 20 Sample products with stock
    - 3 Sample customers
    - 5 Payment methods

### Changed

- 🔄 Updated Eloquent models with proper relationships
- 🔄 Enhanced controllers with Service/Repository pattern
- 🔄 Improved validation rules across all forms
- 🔄 Optimized database queries with eager loading
- 🔄 Refactored POS interface for better UX

### Fixed

- 🐛 Stock calculation errors on concurrent transactions
- 🐛 N+1 query problems in product listings
- 🐛 Cart quantity validation
- 🐛 Permission issues for warehouse stock adjustments
- 🐛 CSRF token handling in SPA-like interfaces

### Documentation

- 📖 Complete README.md with quick start guide
- 📖 Detailed INSTALLATION.md for Windows, Linux, macOS
- 📖 ARCHITECTURE.md with system diagrams
- 📖 DATABASE.md with ERD and complete schema
- 📖 API.md with all routes documented
- 📖 User guides for all three roles (Admin, Cashier, Warehouse)
- 📖 DEVELOPMENT.md for developers
- 📖 DEPLOYMENT.md for production setup
- 📖 CONTRIBUTING.md with guidelines
- 📖 FAQ.md with common questions
- 📖 This CHANGELOG.md

---

## [1.0.0] - 2026-01-07

### 🎉 Initial Release

### Added

#### Core System

- ✨ Laravel 12 framework setup
- ✨ PHP 8.3 support
- ✨ MySQL database configuration
- ✨ Vite for asset bundling
- ✨ Bootstrap 5 integration

#### Basic Features

- ✨ User authentication system
- ✨ Basic product catalog
- ✨ Simple inventory tracking
- ✨ Basic transaction recording
- ✨ Category management

#### Database

- 💾 Initial migration files
- 💾 Basic seeder for demo data
- 💾 User roles table

### Security

- 🔐 Laravel authentication scaffolding
- 🔐 CSRF protection
- 🔐 Password hashing

### Documentation

- 📖 Basic README.md
- 📖 Initial setup instructions

---

## Version History Summary

| Version   | Date       | Description                                             |
| --------- | ---------- | ------------------------------------------------------- |
| **2.5.0** | 2026-01-23 | Branding update, Smart Scanner, and Ngrok compatibility |
| **2.0.0** | 2026-01-09 | Production-ready release with complete features         |
| **1.0.0** | 2026-01-07 | Initial release with basic functionality                |

---

## Upgrade Guide

### From 1.0.0 to 2.0.0

**⚠️ Breaking Changes:**

- Database schema completely redesigned
- Authentication system changed (added NIS field)
- API routes restructured

**Migration Steps:**

1. **Backup existing data:**

    ```bash
    mysqldump -u root -p artika_old > backup_v1.sql
    ```

2. **Pull latest code:**

    ```bash
    git pull origin main
    composer install
    npm install
    ```

3. **Fresh migration (WARNING: Destroys existing data):**

    ```bash
    php artisan migrate:fresh --seed
    ```

4. **Rebuild assets:**

    ```bash
    npm run build
    ```

5. **Update environment:**
    - Review `.env.example` for new variables
    - Update `.env` accordingly

6. **Clear caches:**
    ```bash
    php artisan optimize:clear
    ```

**Data Migration (Optional):**

If you need to migrate old data, create custom seeders to import from v1.0 database.

---

## Planned for Future Versions

### [2.1.0] - Planned

**Features:**

- [ ] Return/refund UI implementation
- [ ] Complete reporting dashboard
- [ ] Advanced sales analytics
- [ ] Product import from CSV/Excel
- [ ] Barcode label printing
- [ ] Email notifications

**Improvements:**

- [ ] Performance optimizations
- [ ] Enhanced error handling
- [ ] Better logging
- [ ] UI/UX refinements

### [3.0.0] - Future

**Major Features:**

- [ ] Shift management system
- [ ] Online ordering integration
- [ ] Customer loyalty program
- [ ] Multi-language support
- [ ] Mobile app (React Native/Flutter)
- [ ] REST API for third-party integrations
- [ ] Advanced inventory forecasting
- [ ] Accounting module integration
- [ ] Employee commission tracking

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute to ARTIKA POS.

---

## Support

- **Documentation:** [README.md](README.md)
- **Issues:** [GitHub Issues](https://github.com/yourusername/artika-pos/issues)
- **Discussions:** [GitHub Discussions](https://github.com/yourusername/artika-pos/discussions)

---

**Last Updated:** 2026-01-23  
**Current Version:** 2.5.0
