# 🚀 Language Switching - Quick Start Guide

## What's Done ✅

Fitur language switching dengan Bahasa Indonesia sebagai default **sudah siap pakai**.

```
✅ Default language: Bahasa Indonesia
✅ Supported: Indonesian (id) & English (en)
✅ Database storage: User language preference
✅ Session storage: Guest language preference
✅ Auto-detection: 3-tier priority system
✅ Translation files: 8 files sudah siap
✅ Middleware: SetLanguage sudah terintegrasi
✅ Routes: Language switching endpoint tersedia
✅ Migration: Database column sudah added
✅ Components: Language selector Blade component ready
```

---

## How to Use It Right Now

### 1. Show Language Selector in Blade Template
```blade
<x-language-selector />
```

### 2. Use Translation in View
```blade
<h1>{{ __('messages.welcome') }}</h1>
<button>{{ __('auth.login_button') }}</button>
<span>{{ __('menu.dashboard') }}</span>
```

### 3. Use Translation in Controller
```php
$title = __('auth.login_title');
$message = __('messages.success');
```

### 4. User Language Preference Auto-Works
- When user logs in → use their saved language
- When user changes language → saved to database
- Works across sessions automatically

---

## Available Translation Keys

Ready-to-use keys in these files:

```
messages.php  → General messages (welcome, login, buttons, etc)
auth.php      → Authentication (login, register, errors)
menu.php      → Navigation labels (dashboard, products, users, etc)
validation.php → Form validation messages
```

**Example usage:**
```blade
{{ __('messages.welcome') }}        <!-- Indonesian: Selamat Datang -->
{{ __('auth.login_button') }}       <!-- Indonesian: Masuk -->
{{ __('menu.products') }}           <!-- Indonesian: Produk -->
{{ __('validation.required') }}     <!-- Indonesian: Bidang ... wajib diisi -->
```

---

## Change Language in URL

```
/language/id  → Switch to Indonesian
/language/en  → Switch to English
```

**Example:**
```html
<a href="/language/id">Indonesian</a>
<a href="/language/en">English</a>
```

The component `<x-language-selector />` automatically generates these links.

---

## Database Structure

The `users` table now has a `language` column:

```sql
ALTER TABLE users ADD language VARCHAR(255) DEFAULT 'id'
```

- Stores user's language preference
- Default: 'id' (Indonesian)
- Automatically used when user logs in
- Updated when user changes language

---

## How It Works Behind The Scenes

```
1. Request comes in
   ↓
2. SetLanguage middleware runs
   ↓
3. Checks in order:
   a) Is user logged in? → Use their saved language
   b) Is there session language? → Use session
   c) Is there ?lang=xx in URL? → Use URL param
   d) Otherwise → Use .env default (Indonesian)
   ↓
4. Language is set for this request
   ↓
5. All __('key') calls use that language
```

---

## What's NOT Done Yet (Optional)

These are recommended for full implementation:

- [ ] Replace hardcoded strings in templates with translation keys
- [ ] Add language selector to navbar/header
- [ ] Translate form validation messages in controllers
- [ ] Add more languages (if needed)
- [ ] Create module-specific translation files (products.php, users.php, etc)

See **LANGUAGE_IMPLEMENTATION_CHECKLIST.md** for detailed todo list.

---

## Test It Works

### Test 1: In Browser
```
1. Open http://localhost/language/en
2. Reload page
3. Header/content should be in English
```

### Test 2: In Code (Tinker)
```bash
php artisan tinker
> echo trans('messages.welcome')
# Output: Selamat Datang

> App::setLocale('en')
> echo trans('messages.welcome')
# Output: Welcome
```

### Test 3: In View
```blade
<p>{{ __('messages.welcome') }}</p>  <!-- Shows in current locale -->
```

---

## Adding Your Own Translations

### For single language feature (e.g., products):

1. Create `resources/lang/id/products.php`
```php
<?php
return [
    'title' => 'Manajemen Produk',
    'add_product' => 'Tambah Produk',
    'delete_product' => 'Hapus Produk',
];
```

2. Create `resources/lang/en/products.php`
```php
<?php
return [
    'title' => 'Product Management',
    'add_product' => 'Add Product',
    'delete_product' => 'Delete Product',
];
```

3. Use in template:
```blade
<h1>{{ __('products.title') }}</h1>
```

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Translations show as `messages.welcome` | Clear cache: `php artisan cache:clear` |
| Language doesn't change | Check `.env` has `APP_LOCALE=id` |
| Errors about 'language' column | Run: `php artisan migrate` |
| Component not found | Ensure file exists: `resources/views/components/language-selector.blade.php` |

---

## File Locations

```
config/
  └─ app.php ........................... Language config
  
app/Http/
  ├─ Middleware/SetLanguage.php ........ Auto-detect middleware
  └─ Controllers/LanguageController.php  Switch handler
  
resources/
  ├─ views/components/
  │  └─ language-selector.blade.php ... Language buttons
  └─ lang/
     ├─ id/ ........................... Indonesian
     │  ├─ messages.php
     │  ├─ auth.php
     │  ├─ menu.php
     │  └─ validation.php
     └─ en/ ........................... English
        ├─ messages.php
        ├─ auth.php
        ├─ menu.php
        └─ validation.php
        
database/migrations/
  └─ 2026_01_16_000000_add_language_to_users_table.php
  
routes/
  └─ web.php .......................... Language route
```

---

## Quick Reference

### Use in Blade Template
```blade
{{ __('key.name') }}
{{ trans('key.name') }}
{{ __('key.name', ['param' => 'value']) }}
```

### Use in Controller
```php
$text = __('key.name');
trans('key.name');
__('key.name', ['param' => 'value']);
```

### Show Language Selector
```blade
<x-language-selector />
```

### Switch Language via URL
```html
<a href="/language/id">Indonesian</a>
<a href="/language/en">English</a>
```

---

## Documentation Files

- **README:** This file
- **[LANGUAGE_SWITCHING.md](./LANGUAGE_SWITCHING.md)** - Complete technical documentation
- **[IMPLEMENTATION_EXAMPLES.md](./IMPLEMENTATION_EXAMPLES.md)** - Code examples & patterns
- **[LANGUAGE_IMPLEMENTATION_CHECKLIST.md](./LANGUAGE_IMPLEMENTATION_CHECKLIST.md)** - Full todo list for integration
- **[LANGUAGE_FEATURE_SUMMARY.md](./LANGUAGE_FEATURE_SUMMARY.md)** - Feature overview

---

## Support Languages

- 🇮🇩 **Indonesian** (id) - DEFAULT
- 🇬🇧 **English** (en)
- 🔧 Extensible to any language (see docs)

---

**Status:** ✅ Ready to Use  
**Date:** 16 Januari 2026  
**Maintainer:** ARTIKA Development Team
