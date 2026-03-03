# 📦 Panduan Pengguna: Staf Gudang

Panduan lengkap bagi Staf Gudang untuk mengelola stok barang dan mutasi inventaris secara akurat.

---

## 📋 Daftar Isi

- [Ringkasan Peran](#ringkasan-peran)
- [Halaman Stok](#halaman-stok)
- [Penyesuaian Stok (Koreksi)](#penyesuaian-stok)
- [Riwayat Mutasi Barang](#riwayat-mutasi-barang)
- [Peringatan Stok Menipis](#peringatan-stok-menipis)

---

## Ringkasan Peran

Sebagai Staf Gudang, tugas utama Anda adalah:

- Memastikan jumlah fisik barang sesuai dengan data di sistem.
- Melakukan pendataan barang masuk (restock) dan barang rusak/kadaluwarsa.
- Melacak pergerakan barang untuk mencegah terjadinya kehilangan.

---

### 1. Daftar Stok Real-Time

Pantau jumlah barang. Sistem akan memberikan peringatan otomatis:

- **Merah**: Stok Kosong.
- **Kuning**: Stok di bawah ambang batas (Segera restock!).
- **Hijau**: Stok Aman.

### 2. Pemasukan Barang (Supplier Purchases)

Setiap restock barang baru **wajib** dicatat melalui sistem Supplier. Anda dapat:

- Input manual satu per satu via form.
- **Import Massal via Excel**: Sangat disarankan untuk pembelanjaan besar. Unduh template khusus supplier tersebut, isi jumlah barang dan harga, lalu unggah. Sistem otomatis menambah stok dan mengupdate Harga Pokok Pembelian (Cost Price).

### 3. Penyesuaian Stok (Adjust)

Jika ada selisih stok (rusak/hilang), gunakan fitur **Adjust Stock**. Berikan alasan yang jelas (misal: "Barang Kadaluwarsa") agar riwayatnya tercatat di _Audit Log_ untuk transparansi.

### 4. Riwayat Pergerakan

Anda dapat melihat detail masuk dan keluarnya barang per tanggal di menu **Stock Movements**. Ini penting untuk melacak ke mana barang "pergi" jika terjadi selisih opname.

---

**Tips:** Pastikan setiap barang yang baru masuk (dari import Excel) segera ditempeli label Barcode agar tidak menghambat kerja Kasir.
