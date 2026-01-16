# Language Switching Feature - Implementation Summary

## ✅ Fitur Sudah Siap Digunakan

Aplikasi ARTIKA sekarang memiliki sistem pengubahan bahasa lengkap dengan **Bahasa Indonesia sebagai default**.

## 📁 File-File yang Dibuat/Dimodifikasi

### Konfigurasi & Middleware
- **`.env`** - Diupdate dengan `APP_LOCALE=id` (bahasa default)
- **`config/app.php`** - Ditambahkan supported languages
- **`app/Http/Middleware/SetLanguage.php`** - Middleware untuk auto-detect bahasa
- **`bootstrap/app.php`** - Middleware terdaftar di app bootstrap

### Controllers
- **`app/Http/Controllers/LanguageController.php`** - Handle language switching

### Translation Files
Dibuat struktur bahasa di `resources/lang/`:

**Bahasa Indonesia (id):**
- `id/messages.php` - Pesan umum
- `id/auth.php` - Pesan login
- `id/menu.php` - Label menu
- `id/validation.php` - Pesan validasi

**English (en):**
- `en/messages.php` - Pesan umum
- `en/auth.php` - Pesan login
- `en/menu.php` - Label menu
- `en/validation.php` - Pesan validasi

### Views Component
- **`resources/views/components/language-selector.blade.php`** - Tombol selector bahasa siap pakai

### Database
- **Migration:** `database/migrations/2026_01_16_000000_add_language_to_users_table.php`
  - Menambah kolom `language` ke tabel `users`
  - Default value: `id` (Indonesia)
  - ✅ Migration sudah dijalankan

### Model
- **`app/Models/User.php`** - Diupdate dengan `language` di `$fillable`

### Routes
- **`routes/web.php`** - Ditambahkan route `/language/{lang}` untuk switching bahasa

### Documentation
- **`LANGUAGE_SWITCHING.md`** - Dokumentasi lengkap fitur
- **`IMPLEMENTATION_EXAMPLES.md`** - Contoh implementasi di view

---

## 🚀 Cara Menggunakan

### 1. **Di Template Blade**

```blade
<!-- Menampilkan text yang diterjemahkan -->
<p>{{ __('messages.welcome') }}</p>

<!-- Tombol selector bahasa -->
<x-language-selector />

<!-- Dengan parameter -->
<p>{{ __('validation.required', ['attribute' => 'Email']) }}</p>
```

### 2. **Di Controller**

```php
$message = __('auth.login_title');
return view('login', ['title' => $message]);
```

### 3. **Di JavaScript (jika diperlukan)**

```javascript
// Redirect ke bahasa lain
window.location.href = '/language/en';
```

---

## 🎯 Cara Kerja Language Switching

1. **Request masuk** → Middleware `SetLanguage` dijalankan
2. **Middleware cek prioritas:**
   - User login → gunakan preferensi user dari database
   - Ada di session → gunakan bahasa dari session
   - Ada parameter `lang` di URL → gunakan parameter
   - Default → gunakan konfigurasi `.env` (Indonesia)
3. **Bahasa diset** dengan `App::setLocale()`
4. **Jika URL punya parameter** → save ke session
5. **Jika user login** → save ke database

---

## 📝 Menambahkan Bahasa Baru

Untuk tambah bahasa (misal Jepang):

1. Update `config/app.php`:
```php
'supported_languages' => [
    'id' => 'Bahasa Indonesia',
    'en' => 'English',
    'ja' => '日本語',
],
```

2. Buat folder `resources/lang/ja/` dengan file:
   - `messages.php`
   - `auth.php`
   - `menu.php`
   - `validation.php`

3. Update route `routes/web.php`:
```php
->where('lang', 'id|en|ja');
```

---

## 🧪 Testing Language Switching

### Test 1: Login Page
```
1. Buka https://artika.test/login
2. Klik bahasa (English atau Indonesia)
3. Cek apakah form label berubah
```

### Test 2: User Preference (Session)
```
1. Buka dashboard (tanpa login)
2. Ubah bahasa dengan klik selector
3. Session akan menyimpan preferensi
```

### Test 3: User Preference (Database)
```
1. Login ke aplikasi
2. Ubah bahasa dari dropdown profil
3. Logout & login kembali
4. Bahasa seharusnya tetap sesuai preferensi
```

### Test 4: URL Parameter
```
1. Kunjungi /dashboard?lang=en
2. Bahasa berubah ke English
3. Preferensi disimpan di session
```

---

## 🔧 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Translasi tidak muncul | Clear cache: `php artisan cache:clear` |
| Bahasa tidak berubah | Check `.env` sudah set `APP_LOCALE=id` |
| Kolom language tidak ada | Jalankan: `php artisan migrate` |
| User preference tidak tersimpan | Check model User punya `language` di `$fillable` |
| Middleware tidak bekerja | Reload aplikasi, check `bootstrap/app.php` |

---

## 📚 Dokumentasi Lengkap

- **[LANGUAGE_SWITCHING.md](./LANGUAGE_SWITCHING.md)** - Panduan detail fitur
- **[IMPLEMENTATION_EXAMPLES.md](./IMPLEMENTATION_EXAMPLES.md)** - Contoh implementasi praktis

---

## ✨ Feature List

✅ Default language: Bahasa Indonesia  
✅ Language selector component  
✅ 3-tier language preference (User → Session → Default)  
✅ Database storage untuk user preference  
✅ Session storage untuk guest  
✅ URL parameter switching  
✅ Middleware auto-detection  
✅ Translation files ready (8 files)  
✅ Controller untuk language change  
✅ Route untuk switching  
✅ Migration untuk database  
✅ Complete documentation  

---

## 📞 Next Steps

1. **Integrate language selector** ke setiap page utama
   - Login page
   - Dashboard
   - Navbar profil

2. **Translate semua interface** menggunakan key-key yang sudah disediakan
   - Replace hardcoded text dengan `{{ __('key') }}`

3. **Add more languages** jika diperlukan (follow panduan di atas)

4. **Test thoroughly** di semua modul (Admin, Cashier, Warehouse)

---

**Status:** ✅ **READY TO USE**  
**Created:** 16 Januari 2026  
**Language Support:** Bahasa Indonesia (default), English, Extensible  
