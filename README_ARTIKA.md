# ARTIKA POS System - Documentation

## 🚀 Project Setup

1.  **Prerequisites**
    *   PHP 8.3+
    *   Composer
    *   MySQL
    *   Node.js & NPM

2.  **Installation**
    ```bash
    # Clone repository (if applicable) or navigate to project folder
    cd c:\laragon\www\ARTIKA

    # Install PHP dependencies
    composer install

    # Install JS dependencies
    npm install
    ```

3.  **Environment Configuration**
    *   Ensure `.env` is configured for MySQL:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=artika
        DB_USERNAME=root
        DB_PASSWORD=
        ```

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate:fresh --seed
    ```
    *   This will create all tables and insert default data:
        - 3 Users (Admin, Cashier, Warehouse)
        - 5 Categories (Snack, Drink, Food, Dairy, Household)
        - 20 Products with stock
        - 3 Sample Customers
        - 5 Payment Methods

5.  **Compile Assets**
    ```bash
    npm run dev
    ```
    *   Keep this running for development
    *   Or use `npm run build` for production

6.  **Running the Application**
    *   **Backend Server**: `php artisan serve` (or use Laragon host `http://artika.test`)
    *   **Frontend Assets**: `npm run dev` (Keep this running for Vite)

---

## 🔑 Login Credentials (Default)

| Role | Username | NIS (Cashier) | Password | Access |
| :--- | :--- | :--- | :--- | :--- |
| **Super Admin** | `admin` | - | `password` | Full Access (Admin Dashboard) |
| **Cashier / Siswa** | `kasir1` | `12345` | `password` | POS Interface (Scan Barcode) |
| **Warehouse** | `gudang` | - | `password` | Stock Management |

*   *Note: Login supports both Username and NIS. If input is numeric (e.g. 12345), system treats it as NIS.*

---

## 📱 Features & Usage

### 1. Point of Sale (POS) - Cashier
*   **Login**: Use NIS `12345` or Username `kasir1`.
*   **Interface**: Access via `/pos`.
*   **Features**:
    *   **Product Search**: Search by name or barcode
    *   **Barcode Scanner**: USB Scanner or Camera Scanner (toggle visibility)
    *   **Cart Management**: Add/remove items, adjust quantities
    *   **Multiple Payment Methods**: Cash, QRIS, Debit, Credit, E-Wallet
    *   **Keyboard Shortcuts**:
        - `F2`: Open checkout
        - `F4`: Hold transaction
        - `F8`: Clear cart
        - `Esc`: Cancel/close modal
    *   **Hold Transaction**: Save current cart for later
    *   **Auto Calculation**: Subtotal, discount, total, change

### 2. Admin Dashboard
*   **Login**: Use Username `admin`.
*   **Features**: View Sales Reports, Manage Users.

### 3. Warehouse Dashboard
*   **Login**: Use Username `gudang`.
*   **Features**: Monitor Low Stock, Manage Products.

---

## 🎨 Design System

### **Brown Theme**
The entire application uses a modern brown color scheme:
- **Primary**: `#85695a` (Warm brown)
- **Accent**: `#c17a5c` (Warm terracotta)
- **Backgrounds**: Light cream tones (`#fdf8f6`, `#f2e8e5`)
- **Typography**: Inter font family (Google Fonts)

### **UI Components**
- Modern card-based design
- Glassmorphism effects
- Smooth animations and transitions
- Custom brown-themed buttons, forms, and modals
- Responsive layout for all devices

---

## 🛠️ Technical Details

*   **Framework**: Laravel 12
*   **Frontend**: Blade + Bootstrap 5 + Custom SCSS
*   **Database**: MySQL with Foreign Keys
*   **Auth**: Custom `AuthController` with Dual Login (Username/NIS) support
*   **Architecture**: MVC with Service & Repository Pattern
*   **Libraries**:
    *   `html5-qrcode` for Camera Scanning
    *   Bootstrap 5 for base components
    *   Custom SCSS for brown theme

---

## 📊 Database Schema

### **Main Tables**
- `users` - User accounts (Admin, Cashier, Warehouse)
- `roles` - User roles
- `categories` - Product categories
- `products` - Product catalog
- `stocks` - Inventory
- `customers` - Customer database
- `transactions` - Sales transactions
- `transaction_items` - Transaction line items
- `held_transactions` - Parked transactions
- `returns` - Return/refund records
- `payment_methods` - Available payment options
- `shifts` - Cashier shifts
- `journals` - Accounting journal entries
- `promos` - Promotions and discounts

---

## ⌨️ Keyboard Shortcuts (POS)

| Key | Action |
|-----|--------|
| `F2` | Open checkout modal |
| `F4` | Hold current transaction |
| `F8` | Clear cart |
| `Esc` | Cancel/close modal |

---

## 🎯 What's New (v2.0)

### **UI/UX Enhancements**
✅ Complete brown theme redesign  
✅ Modern login page with animations  
✅ Enhanced POS interface with card-based product grid  
✅ Smooth hover effects and micro-interactions  
✅ Responsive design for mobile and desktop  

### **New Features**
✅ Product search (real-time filtering)  
✅ Barcode scanner toggle (show/hide)  
✅ Keyboard shortcuts for fast workflow  
✅ Quantity controls in cart (+/- buttons)  
✅ Multiple payment methods (5 options)  
✅ Hold transaction (save for later)  
✅ Customer database (ready for integration)  
✅ Return/refund system (database ready)  

### **Backend Improvements**
✅ Enhanced models with relationships  
✅ Auto-generated invoice numbers  
✅ Transaction scopes and helpers  
✅ Comprehensive database seeder (20 products)  

---

## 📝 Notes

- All database migrations have been successfully executed ✅
- Database seeded with 20 products across 5 categories ✅
- Brown theme design system fully implemented ✅
- POS interface fully functional with keyboard shortcuts ✅
- Hold transaction backend ready ✅
- Customer & Return models ready (UI integration pending) ⏳

---

## 🔄 Future Enhancements

Planned features (database ready, UI pending):
1. Customer selection in POS
2. Loyalty points system
3. Return/refund UI
4. Shift management (open/close kasir)
5. Admin dashboard with analytics
6. Product CRUD interface
7. Print receipt (thermal printer)
8. Discount/promotion rules

---

**Version**: 2.0  
**Last Updated**: 2026-01-09  
**Status**: ✅ Production Ready

