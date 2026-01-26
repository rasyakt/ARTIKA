# 🚀 Panduan Deployment ARTIKA POS

Gunakan panduan ini saat Anda siap memindahkan ARTIKA POS dari komputer lokal (Development) ke server internet (Production).

---

## 📋 Persiapan Produksi

Sebelum mempublikasikan website, pastikan variabel di file `.env` sudah diatur dengan benar:

1. **APP_ENV=production**: Memberitahu Laravel bahwa aplikasi berjalan secara resmi.
2. **APP_DEBUG=false**: Sangat penting! Matikan ini agar pesan error teknis tidak terlihat oleh pengguna umum karena bisa berbahaya bagi keamanan.
3. **APP_URL**: Ubah ke alamat domain asli Anda (e.g., `https://pos.artika.com`).

---

## ⚡ Optimalisasi Kecepatan

Jalankan perintah ini di server produksi untuk performa maksimal:

```bash
php artisan optimize
php artisan view:cache
php artisan config:cache
npm run build
```

---

## 🛡️ Checklist Keamanan (Wajib)

- [ ] **HTTPS (SSL)**: Gunakan sertifikat SSL agar data transaksi terenkripsi.
- [ ] **Database Backup**: Atur pencadangan otomatis harian (cron job).
- [ ] **Folder Permissions**: Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis oleh web server.
- [ ] **Key Rotation**: Pastikan `APP_KEY` unik dan tidak bocor ke publik.

---

## 📂 Struktur Folder Server

Pastikan root domain Anda mengarah ke folder `/public` dari project ARTIKA, bukan ke root foldernya. Ini adalah standar keamanan Laravel agar file inti tidak bisa diakses langsung melalui browser.

---

**Tim Arsitek ARTIKA POS**
