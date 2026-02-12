# 📊 Dokumentasi Database ARTIKA POS

Sistem ARTIKA POS menggunakan database relasional (MySQL/PostgreSQL) dengan skema yang dirancang untuk integritas data keuangan dan stok.

---

## 📋 Tabel Utama

Berikut adalah tabel-tabel krusial dalam sistem:

### 1. Produk & Inventaris

- `categories`: Kategori barang.
- `products`: Katalog produk (barcode, price, cost).
- `stocks`: Jumlah stok real-time per produk.
- `stock_movements`: Riwayat pergerakan stok (In, Out, Adjust).

### 2. Transaksi & Penjualan

- `transactions`: Header transaksi (invoice, total, payment).
- `transaction_items`: Detail item penjualan.
- `held_transactions`: Data transaksi tertunda (Parked).
- `returns`: Pencatatan barang yang dikembalikan oleh pelanggan.

### 3. Keuangan & Biaya

- `journals`: Catatan Ledger otomatis (Debit/Kredit).
- `expenses`: Pengeluaran operasional toko.
- `expense_categories`: Jenis pengeluaran (Gaji, Listrik, dll).
- `suppliers`: Data mitra pemasok barang.

### 4. Otorisasi & Log

- `users`: Data akun pengguna.
- `roles`: Definisi peran (Superadmin, Admin, Manager, Cashier, Warehouse).
- `audit_logs`: Rekam jejak aktivitas user (IP, Device, Action).
- `settings`: Konfigurasi global aplikasi.

---

**Terakhir Diperbarui:** 2026-02-12
