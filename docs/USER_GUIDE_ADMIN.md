# 👨‍💼 ARTIKA POS - Admin User Guide

Panduan lengkap untuk Administrator mengelola sistem ARTIKA POS.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Login](#login)
- [Dashboard](#dashboard)
- [Product Management](#product-management)
- [Category Management](#category-management)
- [User Management](#user-management)
- [Customer Management](#customer-management)
- [Reports](#reports)
- [Best Practices](#best-practices)

---

## Overview

Sebagai **Administrator**, Anda memiliki full access ke sistem ARTIKA POS dengan responsibilities:
- Mengelola master data (products, categories, users)
- Monitoring sales dan inventory
- Generating reports
- Managing system configuration
- User management

---

## Login

1. Buka aplikasi ARTIKA POS
2. Masukkan **Username:** `admin`
3. Masukkan **Password:** (default: `password`)
4. Klik **Login**

> **IMPORTANT:** Ubah password default setelah first login untuk security!

---

## Dashboard

### Overview Widgets

Admin dashboard menampilkan:

1. **Total Sales Today** - Total penjualan hari ini
2. **Total Transactions** - Jumlah transaksi hari ini
3. **Total Products** - Jumlah produk aktif
4. **Low Stock Alerts** - Products dengan stok menipis

### Quick Actions

- **Add Product** - Tambah produk baru
- **View Reports** - Akses laporan penjualan
- **Manage Users** - Kelola user accounts
- **Stock Management** - Monitoring inventory

---

## Product Management

### View All Products

**Path:** Admin → Products

**Features:**
- List semua products dengan pagination
- Search by name atau barcode
- Filter by category
- Actions: Edit, Delete

### Add New Product

**Path:** Admin → Products → Add New

**Steps:**

1. Klik **Add New Product**
2. Fill in product details:
   - **Barcode:** Unique barcode (e.g., `899999999999`)
   - **Product Name:** Nama produk
   - **Category:** Pilih category dari dropdown
   - **Price:** Harga jual ke customer
   - **Cost Price:** Harga beli/cost

3. Klik **Save**

**Validation:**
- Barcode must be unique
- Price dan cost price harus ≥ 0
- All fields required

**Tips:**
- Gunakan barcode standard (EAN-13, UPC)
- Pastikan barcode belum terpakai
- Set reasonable profit margin (Price > Cost Price)

### Edit Product

**Steps:**

1. Di products list, klik **⋮** (ellipsis icon)
2. Pilih **Edit**
3. Update field yang perlu diubah
4. Klik **Update**

**Notes:**
- Barcode bisa diubah jika belum ada transaksi
- Price changes tidak affect transaksi existing

### Delete Product

**Steps:**

1. Di products list, klik **⋮**
2. Pilih **Delete**
3. Konfirmasi delete

> **WARNING:** 
> - Delete product akan menghapus semua stock records
> - Tidak bisa delete jika produk sudah ada di transaksi
> - Pertimbangkan "archive" daripada delete

---

## Category Management

### View Categories

**Path:** Admin → Categories

**Features:**
- List semua categories
- Edit/delete inline
- Add new category

### Add Category

**Steps:**

1. Klik **Add Category**
2. Masukkan:
   - **Name:** Nama category (e.g., "Electronics")
   - **Slug:** URL-friendly slug (e.g., "electronics")
3. Klik **Save**

**Tips:**
- Slug harus lowercase, no spaces (use dash)
- Slug must be unique

### Edit Category

1. Klik icon **Edit** (✏️) di table
2. Update name/slug
3. Save changes

### Delete Category

1. Klik icon **Delete** (🗑️)
2. Konfirmasi delete

> **NOTE:** Tidak bisa delete category jika masih ada products di category tersebut.

---

## User Management

### View All Users

**Path:** Admin → Users

**Displayed Info:**
- Name
- Username
- NIS (if applicable)
- Role
- Actions

### Add New User

**Steps:**

1. Klik **Add New User**
2. Fill in details:
   - **Name:** Full name
   - **Username:** Unique username untuk login
   - **NIS:** (Optional) Untuk cashier/student
   - **Password:** Minimum 8 characters
   - **Role:** Pilih Admin/Cashier/Warehouse

3. Klik **Create User**

**Role Descriptions:**

| Role | Access | Use Case |
|------|--------|----------|
| **Admin** | Full access | System administrator |
| **Cashier** | POS only | Kasir/front counter |
| **Warehouse** | Stock management | Staff gudang |

**Password Policy:**
- Minimum 8 characters
- Recommended: kombinasi huruf + angka + symbol
- User should change password after first login

### Edit User

**Steps:**

1. Klik **⋮** di user row
2. Pilih **Edit**
3. Update fields (except username)
4. Save changes

**Notes:**
- Username tidak bisa diubah setelah created
- Untuk reset password, set new password di edit form

### Delete User

1. Klik **⋮**
2. Pilih **Delete**
3. Konfirmasi

> **WARNING:** Delete user akan set NULL di transaksi created by user tersebut.

---

## Customer Management

### View Customers

**Path:** Admin → Customers

**Features:**
- List all registered customers
- View customer details (name, phone, email, points)
- Edit customer info
- Delete customer

### Add New Customer

**Steps:**

1. Klik **Add Customer**
2. Fill in:
   - **Name:** Customer full name
   - **Phone:** Phone number (unique)
   - **Email:** (Optional)
   - **Address:** (Optional)

3. Klik **Save**

**Loyalty Points:**
- Points automatically calculated based on purchases
- Default: Rp 10,000 spending = 1 point
- Points dapat ditukar untuk discount (future feature)

### Edit Customer

1. Klik **⋮**
2. Edit customer info
3. Save changes

### Customer Analytics (Future)

- Total spending
- Visit frequency
- Favorite products
- Points history

---

## Reports

### Sales Report

**Path:** Admin → Reports → Sales

**Available Reports:**

1. **Daily Sales**
   - Total sales per day
   - Transaction count
   - Average transaction value

2. **Monthly Sales**
   - Sales trend per month
   - Top selling products
   - Revenue analysis

3. **Sales by Cashier**
   - Kasir performance
   - Transaction speed metrics

**Filters:**
- Date range
- Product category
- Payment method

**Export Options:**
- PDF
- Excel (CSV)
- Print

### Inventory Report

**Path:** Admin → Reports → Inventory

**Available Reports:**

1. **Stock Level**
   - Current stock per product
   - Stock value (cost × quantity)

2. **Low Stock Alert**
   - Products below minimum stock
   - Reorder recommendations

3. **Stock Movement**
   - In/out transactions
   - Stock adjustment history

### Financial Report (Future)

- Profit/Loss statement
- Cash flow
- Accounting journals

---

## Best Practices

### Product Management

✅ **DO:**
- Set realistic prices (cover cost + reasonable margin)
- Use standard barcode formats
- Organize products into proper categories
- Regular stock audits
- Archive instead of delete old products

❌ **DON'T:**
- Duplicate barcodes
- Set price below cost price (unless clearance sale)
- Delete products with transaction history
- Forget to update prices when cost changes

### User Management

✅ **DO:**
- Assign users to correct roles
- Enforce strong passwords
- Review user access regularly
- Disable inactive users
- Train users on their role

❌ **DON'T:**
- Share admin credentials
- Use default passwords in production
- Give excessive permissions
- Keep inactive users active

### Data Management

✅ **DO:**
- Regular database backups (daily)
- Export reports periodically
- Clean up old data (archive strategy)
- Monitor system performance

❌ **DON'T:**
- Delete data without backup
- Ignore low stock alerts
- Skip regular backups

---

## System Configuration

### Payment Methods

**Path:** Admin → Settings → Payment Methods (future)

- Enable/disable payment methods
- Configure payment gateways (QRIS, etc)
- Set payment method fees

### Tax Configuration

- Set tax rate (e.g., 10% PPN)
- Apply tax automatically
- Tax-exempt categories

---

## Troubleshooting

### Cannot Delete Product

**Reason:** Product memiliki transaction history atau stock records

**Solution:**
- Archive product instead (set inactive flag)
- Atau clear transaction history dulu (dangerous!)

### User Cannot Login

**Check:**
1. Username/password correct?
2. User active?
3. Role assigned?
4. Browser cookies enabled?

**Solution:**
- Reset user password dari admin panel
- Check role assignment
- Clear browser cache

### Reports Not Loading

**Possible Causes:**
- Database query timeout (too much data)
- Server resource issue

**Solution:**
- Reduce date range for report
- Filter by category untuk smaller dataset
- Contact system administrator

---

## Security Best Practices

### Password Security

1. **Change default passwords immediately**
2. Use strong passwords:
   - Minimum 12 characters
   - Mix uppercase, lowercase, numbers, symbols
3. Don't share admin credentials
4. Rotate passwords every 90 days

### Access Control

1. Follow **principle of least privilege**
   - Cashier = POS only
   - Warehouse = Stock only
   - Admin = Full access

2. Regular access audits
   - Review user list monthly
   - Disable terminated employees immediately

3. Session management
   - Auto logout after inactivity
   - Single session per user

### Data Backup

1. **Daily automatic backups**
2. Store backups offsite atau cloud
3. Test restore procedure monthly
4. Keep multiple backup versions

---

## Need Help?

### Support Channels

- **Technical Issues:** IT Support Team
- **Training:** Request admin training session
- **Feature Requests:** Submit to development team
- **Critical Issues:** Emergency hotline

### Documentation

- [INSTALLATION.md](file:///c:/laragon/www/ARTIKA/INSTALLATION.md) - Installation guide
- [DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md) - Database reference
- [API.md](file:///c:/laragon/www/ARTIKA/API.md) - API documentation

---

**Manage Wisely! 👨‍💼**

**Version:** 2.0  
**Last Updated:** 2026-01-09
