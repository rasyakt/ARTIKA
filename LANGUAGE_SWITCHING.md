# Panduan Fitur Language Switching (Pengubahan Bahasa)

## Ringkasan
Aplikasi ARTIKA sekarang mendukung pengubahan bahasa secara dinamis dengan bahasa Indonesia sebagai default. Pengguna dapat dengan mudah beralih antara Bahasa Indonesia (ID) dan English (EN).

## Fitur Utama

### 1. **Default Language: Bahasa Indonesia**
- Bahasa aplikasi secara default adalah Bahasa Indonesia
- Dikonfigurasi di file `.env` dengan `APP_LOCALE=id`

### 2. **Bahasa yang Didukung**
- **Bahasa Indonesia (id)**: Bahasa default
- **English (en)**: Alternatif bahasa

### 3. **Tiga Metode Penyimpanan Preferensi Bahasa**

#### a) **Database (User Preference)**
- Preferensi bahasa disimpan di database dalam kolom `language` pada tabel `users`
- Otomatis digunakan ketika user login
- Paling prioritas dibandingkan method lain

#### b) **Session**
- Preferensi bahasa disimpan dalam session untuk kunjungan tertentu
- Berlaku selama session aktif
- Tidak memerlukan login

#### c) **Query Parameter**
- Mengubah bahasa melalui URL: `/language/id` atau `/language/en`
- Otomatis menyimpan ke session
- Jika sudah login, juga update database user

## Struktur File Translasi

```
resources/lang/
├── id/                    # Bahasa Indonesia
│   ├── messages.php       # Pesan umum
│   ├── auth.php          # Pesan autentikasi
│   ├── menu.php          # Label menu
│   └── validation.php    # Pesan validasi
└── en/                    # Bahasa Inggris
    ├── messages.php
    ├── auth.php
    ├── menu.php
    └── validation.php
```

## Implementasi Teknis

### Middleware: SetLanguage

**File:** `app/Http/Middleware/SetLanguage.php`

Middleware ini otomatis dijalankan pada setiap request dan menentukan bahasa yang digunakan berdasarkan prioritas:

1. **User Login**: Jika user sudah login, gunakan preferensi bahasa user dari database
2. **Session**: Jika ada preferensi di session, gunakan session
3. **Query Parameter**: Jika ada parameter `lang` di URL, gunakan dan simpan ke session
4. **Default**: Gunakan bahasa default dari konfigurasi

### Controller: LanguageController

**File:** `app/Http/Controllers/LanguageController.php`

Method `change()` menangani perubahan bahasa:
- Validasi bahasa yang didukung
- Simpan ke session
- Update database user (jika sudah login)
- Redirect kembali ke halaman sebelumnya

### Route untuk Language Switching

```php
Route::get('/language/{lang}', [LanguageController::class, 'change'])
    ->name('language.change')
    ->where('lang', 'id|en');
```

### Blade Component: Language Selector

**File:** `resources/views/components/language-selector.blade.php`

Komponen Blade siap pakai untuk menampilkan selector bahasa di view:

```blade
<x-language-selector />
```

Menampilkan tombol untuk memilih bahasa dengan desain yang responsif.

## Migrasi Database

**File:** `database/migrations/2026_01_16_000000_add_language_to_users_table.php`

Migration ini menambahkan kolom `language` ke tabel `users`:

```php
$table->string('language')->default('id')->after('email');
```

## Cara Menggunakan

### 1. **Jalankan Migration**

```bash
php artisan migrate
```

### 2. **Tambahkan Language Selector ke Layout**

Edit file layout Blade Anda (misalnya `resources/views/layouts/app.blade.php`):

```blade
<header>
    <nav>
        <!-- Navigation items -->
        <div style="float: right;">
            <x-language-selector />
        </div>
    </nav>
</header>

@yield('content')
```

### 3. **Gunakan Translation di View**

```blade
<!-- Menggunakan helper __() -->
<h1>{{ __('messages.welcome') }}</h1>

<!-- Menggunakan trans() -->
<p>{{ trans('auth.login_title') }}</p>

<!-- Dengan parameter -->
<p>{{ __('validation.required', ['attribute' => 'Email']) }}</p>
```

### 4. **Gunakan Translation di Controller**

```php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => __('auth.login_title'),
        ]);
    }
}
```

## Menambahkan Bahasa Baru

Untuk menambahkan bahasa baru (misalnya Jepang `ja`):

### 1. **Update Konfigurasi**

Edit `config/app.php`:

```php
'supported_languages' => [
    'id' => 'Bahasa Indonesia',
    'en' => 'English',
    'ja' => '日本語', // Tambah bahasa baru
],
```

### 2. **Buat File Translasi**

Buat folder dan file translasi:

```
resources/lang/ja/
├── messages.php
├── auth.php
├── menu.php
└── validation.php
```

### 3. **Update Route Constraint**

Edit `routes/web.php`:

```php
Route::get('/language/{lang}', [LanguageController::class, 'change'])
    ->name('language.change')
    ->where('lang', 'id|en|ja'); // Tambahkan |ja
```

### 4. **Update Database (Optional)**

Jika ingin update tabel users untuk mendukung bahasa baru:

```php
// Migration tidak perlu diubah, kolom language sudah string
// Cukup pastikan migration sudah jalan
```

## Struktur Lengkap Translasi

### messages.php
Pesan umum yang digunakan di seluruh aplikasi:
- Tombol (Login, Logout, Save, Delete, dll)
- Pesan notifikasi (Success, Error, Warning, Info)
- Label umum (Language, Settings, Profile, dll)

### auth.php
Pesan terkait autentikasi:
- Error message login
- Label form login/register
- Pesan welcome

### menu.php
Label menu navigasi:
- Dashboard
- Products
- Users
- Reports
- Settings
- dll

### validation.php
Pesan validasi form:
- Field required
- Email format
- Password confirmation
- dll

## Contoh Implementasi di View

```blade
<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('auth.login_title') }}</h1>

    <!-- Language Selector -->
    <div style="margin-bottom: 20px;">
        <x-language-selector />
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('auth.email_label') }}</label>
            <input type="email" name="email" class="form-control" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('auth.password_label') }}</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            {{ __('auth.login_button') }}
        </button>
    </form>
</div>
@endsection
```

## Troubleshooting

### Translasi Tidak Muncul
1. Pastikan migration sudah dijalankan
2. Cek spelling key translasi di file lang
3. Pastikan file translasi ada di folder yang benar
4. Clear cache: `php artisan cache:clear`

### Middleware Tidak Bekerja
1. Cek file `bootstrap/app.php` sudah ter-update dengan SetLanguage middleware
2. Clear cache: `php artisan cache:clear`
3. Cek `.env` sudah set `APP_LOCALE=id`

### User Preference Tidak Tersimpan
1. Pastikan kolom `language` ada di tabel users (check migration)
2. Pastikan user model sudah ter-update dengan `language` di `$fillable`
3. Jalankan migration: `php artisan migrate`

## Best Practices

1. **Selalu gunakan translation keys** daripada hardcode text
2. **Maintain consistency** dalam naming convention translasi
3. **Organize by domain** (auth.php, menu.php, messages.php)
4. **Provide user choice** dengan language selector di setiap halaman
5. **Set sensible defaults** sesuai lokasi user
6. **Test all languages** sebelum deployment
7. **Document translation keys** untuk memudahkan maintenance

## Referensi Laravel Documentation

- [Laravel Localization](https://laravel.com/docs/localization)
- [Blade Components](https://laravel.com/docs/blade#components)
- [Middleware](https://laravel.com/docs/middleware)

---

**Dibuat:** 16 Januari 2026
**Versi:** 1.0
