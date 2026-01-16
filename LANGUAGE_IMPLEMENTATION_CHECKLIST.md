# Language Switching - Developer Checklist

## 🎯 Quick Start

Fitur language switching sudah siap digunakan dengan Bahasa Indonesia sebagai default.

---

## ✅ Yang Sudah Selesai (Infrastructure)

### 1. Configuration
- [x] `.env` - Default language set to `id`
- [x] `config/app.php` - Supported languages configured
- [x] Migration - Database column `language` added to users table
- [x] Model - User model updated with `language` fillable

### 2. Middleware & Routing
- [x] `app/Http/Middleware/SetLanguage.php` - Created
- [x] `bootstrap/app.php` - Middleware registered
- [x] `routes/web.php` - Language route added
- [x] `app/Http/Controllers/LanguageController.php` - Created

### 3. Translation Files (8 files)
- [x] `resources/lang/id/messages.php` - Indonesian messages
- [x] `resources/lang/id/auth.php` - Indonesian auth
- [x] `resources/lang/id/menu.php` - Indonesian menu
- [x] `resources/lang/id/validation.php` - Indonesian validation
- [x] `resources/lang/en/messages.php` - English messages
- [x] `resources/lang/en/auth.php` - English auth
- [x] `resources/lang/en/menu.php` - English menu
- [x] `resources/lang/en/validation.php` - English validation

### 4. UI Components
- [x] `resources/views/components/language-selector.blade.php` - Selector component created

### 5. Database
- [x] Migration created and **already executed** ✅

---

## 🚧 TODO: Implementation Checklist

### Phase 1: Basic Integration (Must Do)

#### Login Page
- [ ] Import language selector component
- [ ] Replace hardcoded strings with translations
  - [ ] "Login to ARTIKA" → `__('auth.login_title')`
  - [ ] "Username / NIS" → `__('auth.email_label')`
  - [ ] "Password" → `__('auth.password_label')`
  - [ ] "Login" button → `__('auth.login_button')`
  - [ ] Error messages → use translation keys
- [ ] Add language selector in card header
- [ ] Test login with both languages

**Files to modify:**
```
resources/views/auth/login.blade.php
```

#### Dashboard Layout
- [ ] Create navbar component with language selector
- [ ] Replace menu labels with translations
  - [ ] "Dashboard" → `__('menu.dashboard')`
  - [ ] "Products" → `__('menu.products')`
  - [ ] "Users" → `__('menu.users')`
  - [ ] etc.
- [ ] Add profile dropdown with language options
- [ ] Test navigation in both languages

**Files to modify:**
```
resources/views/layouts/app.blade.php
resources/views/layouts/navbar.blade.php (create if not exists)
```

---

### Phase 2: Admin Pages

- [ ] Admin Dashboard
  - [ ] Translate page title
  - [ ] Translate card headers
  - [ ] Translate button labels

- [ ] Product Management
  - [ ] Translate table headers
  - [ ] Translate form labels
  - [ ] Create `resources/lang/id/products.php` & `en/products.php`
  - [ ] Translate success/error messages

- [ ] User Management
  - [ ] Translate table headers
  - [ ] Translate form labels
  - [ ] Create `resources/lang/id/users.php` & `en/users.php`

- [ ] Category Management
  - [ ] Translate labels and messages

- [ ] Supplier Management
  - [ ] Translate labels and messages

**Pattern to follow:**
```blade
<!-- Before -->
<h1>Product Management</h1>

<!-- After -->
<h1>{{ __('products.title') }}</h1>
```

---

### Phase 3: Cashier (POS) Pages

- [ ] POS Interface
  - [ ] Translate buttons and labels
  - [ ] Create `resources/lang/id/pos.php` & `en/pos.php`

- [ ] Transaction History
  - [ ] Translate table headers
  - [ ] Translate action buttons

- [ ] Return Transaction
  - [ ] Translate form labels
  - [ ] Translate messages

---

### Phase 4: Warehouse Pages

- [ ] Warehouse Dashboard
  - [ ] Translate widgets and stats

- [ ] Stock Management
  - [ ] Translate table headers
  - [ ] Translate form labels
  - [ ] Create `resources/lang/id/warehouse.php` & `en/warehouse.php`

- [ ] Stock Movements
  - [ ] Translate transaction logs
  - [ ] Translate status labels

---

### Phase 5: Validation Messages (Controllers)

- [ ] Audit Controller - Update validation error messages
- [ ] Admin Controller - Update validation error messages
- [ ] Category Controller - Update validation error messages
- [ ] User Controller - Update validation error messages
- [ ] Product Controller - Update validation error messages
- [ ] Transaction Controller - Update validation error messages
- [ ] Warehouse Controller - Update validation error messages

**Pattern:**
```php
// Before
$validated = $request->validate([
    'name' => 'required|string|max:255',
]);

// After (jika perlu custom message)
$validated = $request->validate([
    'name' => 'required|string|max:255',
], [
    'name.required' => __('validation.required', ['attribute' => __('products.product_name')]),
]);
```

---

### Phase 6: Alert/Notification Messages (Controllers)

- [ ] Update all success messages in controllers
- [ ] Update all error messages in controllers
- [ ] Update flash messages

**Pattern:**
```php
// Before
return redirect()->with('success', 'Product added successfully');

// After
return redirect()->with('success', __('products.add_success'));
```

---

### Phase 7: Modal & Dialog Translations

- [ ] Delete confirmation dialogs
- [ ] Form validation messages (frontend)
- [ ] Toast/notification messages

---

### Phase 8: Testing & Quality Assurance

- [ ] Test all pages in Indonesian
- [ ] Test all pages in English
- [ ] Test language switching on each page
- [ ] Test user preference persistence
  - [ ] Change language
  - [ ] Logout
  - [ ] Login - verify language retained
- [ ] Test with new user
  - [ ] Create user
  - [ ] Verify default language is Indonesian
- [ ] Test with existing users
  - [ ] Verify no errors
- [ ] Browser compatibility test
- [ ] Mobile responsiveness test

---

## 📋 Translation Keys Available (Already Created)

### messages.php
```php
'welcome'          // Selamat Datang / Welcome
'dashboard'        // Dashboard
'login'            // Masuk / Login
'logout'           // Keluar / Logout
'register'         // Daftar / Register
'email'            // Email
'password'         // Kata Sandi / Password
'remember_me'      // Ingat Saya / Remember Me
'forgot_password'  // Lupa Kata Sandi / Forgot Password
'save'             // Simpan / Save
'edit'             // Ubah / Edit
'delete'           // Hapus / Delete
'create'           // Buat / Create
'search'           // Cari / Search
'filter'           // Filter
'export'           // Ekspor / Export
'import'           // Impor / Import
'back'             // Kembali / Back
'next'             // Selanjutnya / Next
'previous'         // Sebelumnya / Previous
'language'         // Bahasa / Language
'select_language'  // Pilih Bahasa / Select Language
'settings'         // Pengaturan / Settings
'profile'          // Profil / Profile
'help'             // Bantuan / Help
'about'            // Tentang / About
'error'            // Kesalahan / Error
'success'          // Berhasil / Success
'warning'          // Peringatan / Warning
'info'             // Informasi / Information
```

### auth.php
```php
'failed'           // Email atau kata sandi salah
'password'         // Kata sandi yang disediakan salah
'throttle'         // Terlalu banyak upaya login
'login_title'      // Masuk ke ARTIKA
'register_title'   // Daftar ARTIKA
'email_label'      // Alamat Email
'password_label'   // Kata Sandi
'confirm_password' // Konfirmasi Kata Sandi
'name_label'       // Nama Lengkap
'remember'         // Ingat saya
'login_button'     // Masuk
'register_button'  // Daftar
'welcome'          // Selamat Datang Kembali
```

### menu.php
```php
'dashboard'        // Dashboard
'products'         // Produk / Products
'categories'       // Kategori / Categories
'users'            // Pengguna / Users
'suppliers'        // Supplier
'transactions'     // Transaksi / Transactions
'sales'            // Penjualan / Sales
'returns'          // Pengembalian / Returns
'inventory'        // Inventaris / Inventory
'stock'            // Stok / Stock
'reports'          // Laporan / Reports
'settings'         // Pengaturan / Settings
'profile'          // Profil / Profile
'audit_logs'       // Log Audit / Audit Logs
'promotions'       // Promosi / Promotions
'payment_methods'  // Metode Pembayaran / Payment Methods
```

### validation.php
```php
'required'         // Bidang :attribute wajib diisi
'email'            // Bidang :attribute harus merupakan email
'min'              // Bidang :attribute harus minimal :min karakter
'max'              // Bidang :attribute tidak boleh lebih dari :max karakter
'confirmed'        // Konfirmasi :attribute tidak cocok
'unique'           // :attribute sudah terdaftar
'numeric'          // :attribute harus berupa angka
'exists'           // :attribute yang dipilih tidak valid
```

---

## 💡 Quick Tips

1. **Use Helper Function:**
   ```blade
   {{ __('key') }}  <!-- Most common -->
   {{ trans('key') }} <!-- Alternative -->
   ```

2. **Use Dot Notation:**
   ```blade
   {{ __('products.title') }}     <!-- Accesses products.php file -->
   {{ __('auth.login_button') }}  <!-- Accesses auth.php file -->
   ```

3. **With Parameters:**
   ```blade
   {{ __('validation.min', ['min' => 8]) }}
   ```

4. **In Controllers:**
   ```php
   $message = __('auth.login_title');
   return view('page', ['title' => $message]);
   ```

5. **Create New Translation File:**
   - Create `resources/lang/id/yourmodule.php`
   - Create `resources/lang/en/yourmodule.php`
   - Use `__('yourmodule.key')`

---

## 📊 Implementation Progress Template

```markdown
## Language Implementation Progress

### Admin Module
- [ ] Dashboard - 0% done
- [ ] Products - 0% done
- [ ] Categories - 0% done
- [ ] Users - 0% done
- [ ] Suppliers - 0% done

### Cashier Module
- [ ] POS - 0% done
- [ ] Returns - 0% done

### Warehouse Module
- [ ] Dashboard - 0% done
- [ ] Stock - 0% done

### Shared Components
- [ ] Login - 0% done
- [ ] Navbar - 0% done
- [ ] Alerts - 0% done

**Overall Progress: 0%**
```

---

## 🎓 Resources

- [LANGUAGE_SWITCHING.md](./LANGUAGE_SWITCHING.md) - Complete documentation
- [IMPLEMENTATION_EXAMPLES.md](./IMPLEMENTATION_EXAMPLES.md) - Code examples
- [Laravel Localization Docs](https://laravel.com/docs/localization)

---

## 🎉 Ready to Go!

The infrastructure is complete. Start with Phase 1 (Login & Dashboard) and work your way through the phases.

**Estimated Time per Phase:**
- Phase 1: 2-3 hours
- Phase 2: 4-5 hours
- Phase 3: 2-3 hours
- Phase 4: 2-3 hours
- Phase 5-7: 3-4 hours
- Phase 8: 2-3 hours

**Total Estimated:** 16-22 hours for complete implementation

---

**Last Updated:** 16 Januari 2026  
**Status:** ✅ Infrastructure Complete, Ready for Implementation
