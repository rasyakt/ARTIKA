# 🛒 ARTIKA POS - Cashier User Guide

Panduan lengkap untuk Kasir/Cashier menggunakan sistem POS ARTIKA.

---

## 📋 Table of Contents

- [Getting Started](#getting-started)
- [Login](#login)
- [POS Interface Overview](#pos-interface-overview)
- [Processing Transactions](#processing-transactions)
- [Using Barcode Scanner](#using-barcode-scanner)
- [Hold Transactions](#hold-transactions)
- [Keyboard Shortcuts](#keyboard-shortcuts)
- [Payment Methods](#payment-methods)
- [Troubleshooting](#troubleshooting)

---

## Getting Started

Sebagai **Kasir/Cashier**, tugas utama Anda adalah:

- Melayani pelanggan di POS terminal
- Memproses transaksi penjualan
- Menerima pembayaran (cash, QRIS, card, dll)
- Mencetak struk untuk pelanggan

---

## Login

### Method 1: Login dengan NIS (Nomor Induk Siswa)

Jika Anda adalah siswa/pelajar yang bekerja sebagai kasir:

1. Buka aplikasi ARTIKA POS
2. Di halaman login, masukkan **NIS** Anda
3. Masukkan **password**
4. Klik **Login**

**Contoh:**

```
NIS: 12345
Password: password
```

### Method 2: Login dengan Username

1. Buka aplikasi ARTIKA POS
2. Di halaman login, masukkan **Username**
3. Masukkan **password**
4. Klik **Login**

**Contoh:**

```
Username: kasir1
Password: password
```

> **Note:** Sistem secara otomatis mendeteksi apakah input adalah NIS (angka) atau Username (huruf).

### After Login

Setelah berhasil login, Anda akan diarahkan ke **POS Interface**.

---

## POS Interface Overview

Interface POS terdiri dari beberapa bagian utama:

### 1. Product Grid (Kiri)

- Menampilkan semua produk dalam bentuk card/grid
- Setiap card menampilkan:
    - Nama produk
    - Harga
    - Kategori
    - Stok tersedia
- **Search bar** di atas untuk mencari produk

### 2. Shopping Cart (Kanan)

- Menampilkan item yang sudah ditambahkan
- Untuk setiap item, ditampilkan:
    - Nama produk
    - Harga satuan
    - Quantity
    - Subtotal
- Tombol **+/-** untuk adjust quantity
- Tombol **Remove** (🗑️) untuk hapus item

### 3. Scanner Section

- Tombol **Toggle Scanner** untuk show/hide barcode scanner
- Input field untuk manual barcode entry
- Camera scanner (jika diaktifkan)

### 4. Cart Summary (Bawah Kanan)

- **Subtotal:** Total sebelum diskon/pajak
- **Discount:** Diskon yang diterapkan
- **Tax:** Pajak (jika ada)
- **Total:** Total yang harus dibayar

### 5. Action Buttons

- **Checkout (F2):** Proses pembayaran
- **Hold (F4):** Simpan transaksi untuk nanti
- **Clear (F8):** Kosongkan cart

---

## Processing Transactions

### Step-by-Step: Complete Transaction

#### Step 1: Add Products to Cart

**Method 1: Click Product Card**

1. Cari produk di grid
2. Klik pada product card
3. Produk otomatis ditambahkan ke cart dengan quantity 1

**Method 2: Scan Barcode**

1. Gunakan USB scanner atau camera scanner
2. Scan barcode produk
3. Produk otomatis ditambahkan ke cart

**Method 3: Manual Barcode Entry**

1. Ketik barcode di input field scanner
2. Tekan Enter
3. Produk ditambahkan ke cart

#### Step 2: Adjust Quantity (Opsional)

- Klik tombol **+** untuk tambah quantity
- Klik tombol **-** untuk kurangi quantity
- Atau klik angka quantity dan ketik manual

#### Step 3: Remove Item (Opsional)

- Klik tombol **🗑️ Remove** pada item yang ingin dihapus

#### Step 4: Apply Discount (Opsional)

- Masukkan discount amount di field discount
- Subtotal akan dikurangi discount

#### Step 5: Checkout

1. Klik tombol **Checkout** (atau tekan **F2**)
2. Modal checkout akan muncul

#### Step 6: Select Payment Method

Pilih salah satu payment method:

- **Cash** (Tunai)
- **Non-Cash** (QRIS, Debit, Credit, E-Wallet) - _Requires payment proof upload_

#### Step 7: Enter Payment Details

**Jika Cash:**

1. Masukkan jumlah uang yang diterima di field "Cash Amount"
2. Sistem otomatis menghitung kembalian
3. Kembalian ditampilkan di field "Change"

**Jika Non-Cash:**

1. Klik payment method **Non-Cash**
2. Gunakan kamera HP atau pilih dari galeri untuk mengunggah **Bukti Pembayaran** (Payment Proof)
3. Tunggu hingga preview gambar muncul
4. Konfirmasi pembayaran berhasil (klik Confirm)
5. Sistem akan menyimpan file bukti tersebut sebagai referensi audit.

#### Step 8: Complete Transaction

1. Klik tombol **Complete Transaction**
2. Tunggu proses (sistem akan update stock otomatis)
3. Receipt akan ditampilkan

#### Step 9: Print Receipt (Opsional)

1. Klik **Print Receipt** di halaman success
2. Struk akan keluar dari printer
3. Berikan struk ke pelanggan

#### Step 10: Start New Transaction

1. Cart otomatis dikosongkan setelah transaksi selesai
2. Siap untuk transaksi berikutnya

---

## Using Barcode Scanner

### USB Barcode Scanner

**Setup:**

1. Pastikan USB scanner terhubung ke komputer
2. Test scanner dengan scan produk
3. Barcode akan otomatis ter-input dan produk ditambahkan ke cart

**Tips:**

- USB scanner bekerja seperti keyboard, langsung input barcode
- Tidak perlu klik field apapun, cukup scan
- Sangat cepat untuk transaksi dengan banyak item

### Camera Scanner

**Setup:**

1. Klik tombol **Toggle Scanner** di POS interface
2. Izinkan akses kamera jika diminta browser
3. Scanner camera akan muncul

**Using Camera Scanner:**

1. Arahkan kamera ke barcode produk
2. Tunggu hingga scanner mengenali barcode (biasanya 1-2 detik)
3. Produk otomatis ditambahkan ke cart setelah barcode terdeteksi

**Tips:**

- Sistem memiliki **Smart Cooldown** selama 2.5 detik. Jika barcode yang sama tertahan di depan kamera, item tidak akan "spamming" berkali-kali.
- Dengarkan suara **Beep** sebagai indikasi scan berhasil.
- Lihat notifikasi **Toast** (pojok layar) yang mengonfirmasi nama produk yang baru discan.
- Pastikan pencahayaan cukup dan jarak 15-20cm.

**Close Camera Scanner:**

- Klik tombol **Toggle Scanner** lagi untuk hide scanner

---

## Hold Transactions

### When to Use Hold Transaction

Gunakan fitur **Hold Transaction** ketika:

- Pelanggan perlu mengambil barang tambahan
- Pelanggan belum siap bayar
- Ada antrian dan perlu serve pelanggan lain dulu
- Pelanggan lupa dompet dan perlu ke ATM

### How to Hold Transaction

1. Tambahkan produk ke cart seperti biasa
2. Klik tombol **Hold** (atau tekan **F4**)
3. (Opsional) Masukkan note/catatan, contoh: "Pelanggan Pak Budi"
4. Klik **Save**
5. Transaksi tersimpan dan cart dikosongkan
6. Anda bisa serve pelanggan lain

### How to Resume Held Transaction

1. Klik tombol **Held Transactions** di POS interface
2. List of held transactions akan muncul
3. Klik **Resume** pada transaksi yang ingin dilanjutkan
4. Cart akan di-load dengan item dari held transaction
5. Lanjutkan proses checkout

### Delete Held Transaction

1. Buka **Held Transactions** list
2. Klik **Delete** (🗑️) pada transaksi yang tidak jadi
3. Konfirmasi delete

> **Note:** Held transactions hanya tersimpan selama session. Jika logout, held transactions akan hilang (tergantung konfigurasi).

---

## Keyboard Shortcuts

Untuk mempercepat workflow, gunakan keyboard shortcuts berikut:

| Shortcut | Action           | Description                     |
| -------- | ---------------- | ------------------------------- |
| **F2**   | Open Checkout    | Membuka modal checkout          |
| **F4**   | Hold Transaction | Simpan transaksi untuk nanti    |
| **F8**   | Clear Cart       | Kosongkan seluruh cart          |
| **Esc**  | Cancel           | Cancel/close modal yang terbuka |

**Tips Menggunakan Shortcuts:**

- Setelah scan semua produk, langsung tekan **F2** untuk checkout
- Jika pelanggan cancel, tekan **F8** untuk clear cart
- Hold transaction dengan **F4** tanpa perlu klik mouse

---

## Payment Methods

### 1. Cash (Tunai)

**Process:**

1. Pilih payment method **Cash**
2. Masukkan nominal uang yang diterima dari pelanggan
3. Sistem otomatis hitung kembalian
4. Verifikasi jumlah kembalian di layar
5. Complete transaction
6. Berikan kembalian ke pelanggan

**Tips:**

- Pastikan nominal cash yang diinput benar
- Cek kembalian sebelum complete
- Jika salah input, edit di field "Cash Amount"

### 2. QRIS

**Process:**

1. Pilih payment method **QRIS**
2. Tampilkan QR code ke pelanggan (dari payment terminal/app)
3. Tunggu pelanggan scan dan bayar
4. Verifikasi pembayaran berhasil di app payment
5. Complete transaction

### 3. Debit Card

**Process:**

1. Pilih payment method **Debit Card**
2. Swipe/insert/tap kartu debit pelanggan di EDC machine
3. Pelanggan input PIN
4. Tunggu approval dari bank
5. Complete transaction setelah approved
6. Print struk EDC untuk pelanggan

### 4. Credit Card

**Process:**

1. Pilih payment method **Credit Card**
2. Swipe/insert/tap kartu kredit di EDC machine
3. Pelanggan input PIN atau tanda tangan
4. Tunggu approval
5. Complete transaction
6. Print struk EDC

### 5. E-Wallet (GoPay, OVO, Dana, etc.)

**Process:**

1. Pilih payment method **E-Wallet**
2. Tampilkan QR code atau buka app e-wallet
3. Pelanggan scan/transfer
4. Verifikasi pembayaran masuk
5. Complete transaction

---

## Common Scenarios

### Scenario 1: Fast Transaction (USB Scanner)

**Fastest workflow:**

1. Greeting: "Selamat datang!"
2. Scan semua produk dengan USB scanner
3. Tekan **F2**
4. Pilih payment method (Cash)
5. Input cash amount
6. Tekan Enter atau click Complete
7. Print receipt
8. Berikan struk + kembalian: "Terima kasih!"

**Time:** ~30 detik untuk 5-10 items

### Scenario 2: Transaction with Discount

**Example: Diskon member 10%**

1. Add all products to cart
2. Calculate 10% dari subtotal (atau ada auto discount)
3. Input discount amount di field discount
4. Tekan **F2** untuk checkout
5. Complete transaction

### Scenario 3: Multiple Customers Waiting

**Use Hold Transaction:**

1. Serve customer A:
    - Add items to cart
    - Customer A belum siap bayar
    - Tekan **F4** to hold
    - Note: "Customer A - Baju Merah"

2. Serve customer B:
    - Add items untuk customer B
    - Complete transaction customer B

3. Resume customer A:
    - Click **Held Transactions**
    - Resume "Customer A - Baju Merah"
    - Complete transaction

### Scenario 4: Wrong Item Added

**Quick fix:**

1. Klik tombol **🗑️** di item yang salah
2. Item langsung dihapus dari cart
3. Lanjut checkout

**Atau:**

- Tekan **F8** untuk clear semua cart
- Scan ulang dari awal (jika banyak item salah)

---

## Troubleshooting

### Problem: Barcode Scanner Tidak Bekerja

**Solution:**

1. Cek koneksi USB scanner
2. Test di notepad - scan barcode, apakah muncul?
3. Jika muncul di notepad tapi tidak di POS, refresh page
4. Jika tetap tidak work, gunakan manual entry atau camera scanner

### Problem: Product Not Found

**Solution:**

1. Cek apakah barcode benar
2. Coba manual search dengan nama produk
3. Jika produk tidak muncul, kemungkinan stok habis atau belum di-input
4. Hubungi admin/supervisor

### Problem: Insufficient Stock

**Error:** "Stok tidak mencukupi untuk produk: [Nama Produk]"

**Solution:**

1. Kurangi quantity item di cart
2. Atau remove item tersebut
3. Inform pelanggan bahwa stok limited
4. Laporkan ke warehouse staff untuk restock

### Problem: Payment Failed

**Solution:**

1. Coba payment method lain
2. Jika cash, pastikan nominal cukup
3. Jika card, coba swipe ulang atau gunakan card lain
4. Jika QRIS/E-Wallet, pastikan koneksi internet stabil

### Problem: Printer Not Working

**Solution:**

1. Cek koneksi printer
2. Cek kertas struk masih ada
3. Restart printer
4. Jika urgent, catat manual invoice number
5. Print ulang nanti dari menu Transactions

### Problem: Forgot to Give Receipt

**Solution:**

1. Note invoice number dari layar
2. Buka menu **Transaction History** (jika ada akses)
3. Search by invoice number
4. Reprint receipt

---

## Best Practices

### ✅ DO:

- **Greet customer** dengan ramah
- **Verify items** di cart sebelum checkout
- **Double check payment amount** sebelum complete
- **Give receipt** ke setiap pelanggan
- **Use keyboard shortcuts** untuk speed
- **Clean cart** dengan F8 jika customer cancel
- **Hold transaction** jika customer not ready

### ❌ DON'T:

- Jangan complete transaction sebelum payment received
- Jangan lupa berikan kembalian
- Jangan skip printing receipt
- Jangan logout dengan ada held transactions
- Jangan input wrong quantity (selalu verify)

---

## Tips for Efficiency

1. **Master keyboard shortcuts** - F2, F4, F8, Esc
2. **Use USB scanner** untuk speed (lebih cepat dari camera/manual)
3. **Prepare payment terminal** sebelum hit checkout
4. **Organize workspace** - scanner, keyboard, cash drawer accessible
5. **Anticipate customer needs** - kantong belanja, tisu, dll

---

## Need Help?

- **Technical issues:** Hubungi IT support / Admin
- **Product questions:** Hubungi supervisor / warehouse
- **Customer complaints:** Escalate ke supervisor

---

**Happy Serving Customers! 🛒**

**Version:** 2.5  
**Last Updated:** 2026-01-23
