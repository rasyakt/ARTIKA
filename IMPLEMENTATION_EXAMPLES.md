# Contoh Implementasi Language Switching di Aplikasi ARTIKA

## 1. Tambahkan Language Selector ke Login Page

Edit file `resources/views/auth/login.blade.php`, tambahkan component language selector setelah header:

```blade
<div class="card-header">
    <h1 class="brand-logo mb-2">ARTIKA</h1>
    <p class="brand-subtitle mb-0">Smart Point of Sale System</p>
    
    <!-- Tambahkan language selector di sini -->
    <div style="margin-top: 15px; border-top: 1px solid #e0cec7; padding-top: 12px;">
        <x-language-selector />
    </div>
</div>
```

## 2. Gunakan Translasi dalam Login Form

Contoh penggunaan translasi di form login:

```blade
<form action="{{ route('login') }}" method="POST" id="loginForm">
    @csrf
    
    <!-- Username field -->
    <div class="mb-4 input-group">
        <label for="username" class="form-label">{{ __('auth.email_label') }}</label>
        <div class="input-wrapper">
            <span class="input-icon" aria-hidden>
                <svg class="icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4 20a8 8 0 0116 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <input type="text" name="username" class="form-control with-icon" id="username"
                placeholder="{{ __('auth.email_label') }}" required autofocus aria-label="Username or NIS"
                value="{{ old('username') }}">
        </div>
    </div>

    <!-- Password field -->
    <div class="mb-4 input-group">
        <label for="password" class="form-label">{{ __('auth.password_label') }}</label>
        <div class="input-wrapper">
            <span class="input-icon" aria-hidden>
                <svg class="icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="11" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 11V8a5 5 0 0110 0v3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <input type="password" name="password" class="form-control with-icon" id="password"
                placeholder="{{ __('auth.password_label') }}" required aria-label="Password">
        </div>
    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-login w-100">
        {{ __('auth.login_button') }}
    </button>
</form>
```

## 3. Tambahkan Language Selector ke Dashboard

Contoh menambahkan language selector ke navigation bar di dashboard:

```blade
<!-- Di resources/views/layouts/app.blade.php atau navbar component -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">ARTIKA</a>
        
        <div class="navbar-nav ms-auto">
            <!-- Profile Dropdown -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('messages.profile') }}</a></li>
                    <li><hr class="dropdown-divider"></li>
                    
                    <!-- Language Selection in Dropdown -->
                    <li class="dropdown-header">{{ __('messages.language') }}</li>
                    <li>
                        @foreach(config('app.supported_languages', ['id' => 'Bahasa Indonesia', 'en' => 'English']) as $code => $name)
                            <a class="dropdown-item {{ App::getLocale() === $code ? 'active' : '' }}" 
                               href="{{ route('language.change', ['lang' => $code]) }}">
                                {{ $name }}
                            </a>
                        @endforeach
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item">{{ __('messages.logout') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
```

## 4. Menerjemahkan Menu di Admin Dashboard

Edit `resources/views/admin/dashboard.blade.php`:

```blade
<div class="sidebar">
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> {{ __('menu.dashboard') }}
        </a>
        <a class="nav-link" href="{{ route('admin.products') }}">
            <i class="bi bi-box"></i> {{ __('menu.products') }}
        </a>
        <a class="nav-link" href="{{ route('admin.categories') }}">
            <i class="bi bi-tags"></i> {{ __('menu.categories') }}
        </a>
        <a class="nav-link" href="{{ route('admin.users') }}">
            <i class="bi bi-people"></i> {{ __('menu.users') }}
        </a>
        <a class="nav-link" href="{{ route('admin.suppliers') }}">
            <i class="bi bi-shop"></i> {{ __('menu.suppliers') }}
        </a>
    </nav>
</div>
```

## 5. Translasi di Controller

Contoh di controller untuk error messages:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validasi form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sku' => 'required|string|unique:products',
        ]);

        try {
            Product::create($validated);
            
            return redirect()->route('admin.products')
                ->with('success', __('messages.success') . ': ' . __('menu.products') . ' ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('messages.error') . ': ' . $e->getMessage());
        }
    }
}
```

## 6. Menambahkan Lebih Banyak Translasi

Jika ingin menambahkan translasi untuk modul tertentu, buat file baru:

**resources/lang/id/products.php:**
```php
<?php

return [
    'title' => 'Manajemen Produk',
    'add_product' => 'Tambah Produk',
    'edit_product' => 'Ubah Produk',
    'delete_product' => 'Hapus Produk',
    'product_name' => 'Nama Produk',
    'product_price' => 'Harga Produk',
    'product_sku' => 'SKU Produk',
    'product_category' => 'Kategori Produk',
    'product_stock' => 'Stok Produk',
    'no_products' => 'Tidak ada produk',
    'add_success' => 'Produk berhasil ditambahkan',
    'update_success' => 'Produk berhasil diperbarui',
    'delete_success' => 'Produk berhasil dihapus',
];
```

**resources/lang/en/products.php:**
```php
<?php

return [
    'title' => 'Product Management',
    'add_product' => 'Add Product',
    'edit_product' => 'Edit Product',
    'delete_product' => 'Delete Product',
    'product_name' => 'Product Name',
    'product_price' => 'Product Price',
    'product_sku' => 'Product SKU',
    'product_category' => 'Product Category',
    'product_stock' => 'Product Stock',
    'no_products' => 'No products found',
    'add_success' => 'Product added successfully',
    'update_success' => 'Product updated successfully',
    'delete_success' => 'Product deleted successfully',
];
```

Kemudian gunakan di view:
```blade
<h1>{{ __('products.title') }}</h1>
<a href="{{ route('admin.products.create') }}" class="btn btn-primary">
    {{ __('products.add_product') }}
</a>
```

## Testing

Untuk test language switching:

1. **Test Login Page:**
   - Buka aplikasi di `https://artika.test/login`
   - Klik tombol bahasa Indonesia / English
   - Cek apakah form label berubah

2. **Test After Login:**
   - Login dengan user
   - Klik menu bahasa
   - Cek apakah seluruh interface berubah bahasa

3. **Test Database Save:**
   - Ubah bahasa saat sudah login
   - Logout
   - Login kembali
   - Cek apakah bahasa tersimpan sesuai preferensi user

4. **Test with Query Parameter:**
   - Kunjungi URL: `https://artika.test/dashboard?lang=en`
   - Cek apakah bahasa berubah
   - Logout dan login
   - Cek apakah bahasa tetap disimpan

---

**Tips:**
- Pastikan selalu gunakan `{{ __('key') }}` atau `trans('key')` untuk text yang ditampilkan
- Maintain consistency dalam naming convention translation keys
- Test dengan multiple browser untuk memastikan session handling bekerja baik
