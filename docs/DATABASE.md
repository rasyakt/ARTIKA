# 📊 Dokumentasi Database ARTIKA POS

Sistem ARTIKA POS menggunakan database relasional (MySQL) dengan skema yang dirancang untuk mendukung integritas data keuangan dan stok.

---

## 📋 Tabel Utama

Sistem ini memiliki total 19 tabel, berikut adalah tabel-tabel krusial:

### 1. Tabel Produk & Kategori

- `categories`: Menyimpan kategori barang (e.g., Makanan, Minuman).
- `products`: Katalog produk lengkap dengan nama, barcode, harga beli (cost), dan harga jual.
- `stocks`: Menyimpan jumlah stok saat ini untuk setiap produk.

### 2. Tabel Transaksi (POS)

- `transactions`: Header transaksi yang menyimpan nomor invoice, total belanja, metode pembayaran, dan kasir yang bertugas.
- `transaction_items`: Detail barang yang dibeli dalam satu transaksi (produk, kuantitas, harga saat itu, subtotal).
- `held_transactions`: Menyimpan data transaksi yang "ditunda" (Parked Transactions).

### 3. Tabel Keuangan & Log

- `journals`: Catatan akuntansi (debit/kredit) otomatis setiap kali transaksi terjadi.
- `stock_movements`: Riwayat pergerakan stok (masuk/keluar/penyesuaian) yang sangat detail.
- `audit_logs`: Rekam jejak aktivitas user untuk keamanan sistem.

### 4. Tabel Pengguna

- `users`: Data akun pengguna.
- `roles`: Definisi peran (Admin, Kasir, Gudang).

---

## 🔗 Relasi Kunci (Foreign Keys)

- `transaction_items.transaction_id` → `transactions.id`
- `transaction_items.product_id` → `products.id`
- `stocks.product_id` → `products.id`
- `products.category_id` → `categories.id`

---

## 🛡️ Integritas Data

Semua proses yang melibatkan banyak tabel (seperti Checkout) dilakukan di dalam **Database Transaction**. Jika penulisan data ke salah satu tabel gagal, maka seluruh data terkait akan dibatalkan otomatis agar tidak terjadi ketidaksinkronan stok dan uang.

---

**Terakhir Diperbarui:** 2026-01-26
