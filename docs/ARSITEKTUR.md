# 🏗️ Arsitektur Sistem ARTIKA POS

Dokumentasi teknis lengkap mengenai arsitektur sistem, pola desain (design patterns), dan detail implementas ARTIKA POS.

---

## 📋 Daftar Isi

- [Ringkasan](#ringkasan)
- [Diagram Arsitektur](#diagram-arsitektur)
- [Struktur Direktori](#struktur-direktori)
- [Pola Desain](#pola-desain)
- [Alur Autentikasi](#alur-autentikasi)
- [Siklus Hidup Request](#siklus-hidup-request)
- [Lapisan Keamanan](#lapisan-keamanan)

---

ARITKA POS dibangun menggunakan framework **Laravel 12** (PHP 8.2+) dengan arsitektur **MVC (Model-View-Controller)** yang diperkuat dengan **Service Layer** dan **Repository Pattern**.

---

## Diagram Arsitektur

```mermaid
graph TB
    subgraph "Lapisan Klien"
        Browser[Web Browser]
        Scanner[USB/Camera Scanner]
    end

    subgraph "Lapisan Presentasi"
        Routes[Routes/web.php]
        Middleware[Middleware/Role Access]
        Controllers[Controllers]
    end

    subgraph "Lapisan Logika Bisnis"
        Services[Services/TransactionService]
        Repositories[Repositories]
    end

    subgraph "Lapisan Data"
        Models[Eloquent Models]
        Database[(MySQL/PostgreSQL)]
    end

    Browser --> Routes
    Scanner --> Browser
    Routes --> Middleware
    Middleware --> Controllers
    Controllers --> Services
    Services --> Repositories
    Repositories --> Models
    Models --> Database
```

---

## Lapisan Otorisasi (RBAC)

Sistem menggunakan Role-Based Access Control dengan 5 tingkatan:

1. **Superadmin**: Akses teknis tingkat tinggi (maintenance, logs, settings).
2. **Admin**: Pengelola utama operasional (master data, user, reports).
3. **Manager**: Pengawas harian (audit, koreksi transaksi, reports).
4. **Cashier**: Operator transaksi (POS, history).
5. **Warehouse**: Pengelola stok (opname, movements).

---

## Keamanan

1. **Proteksi CSRF**: Semua request POST/PUT/DELETE dilindungi token CSRF.
2. **Hashing Password**: Menggunakan algoritma Bcrypt via Laravel.
3. **Audit Logging**: Setiap aksi krusial (hapus data, update stok) dicatat dalam tabel `audit_logs` bersama data IP dan User-Agent.

## Pengelolaan File & Keamanan Upload

ARTIKA POS menerapkan teknik keamanan tingkat tinggi untuk mencegah kerentanan upload file (seperti RCE, backdoor, shell script, atau polyglot):

1. **Pemrosesan Gambar Aman (Secure Re-encoding)**: File gambar (bukti bayar, foto produk) diproses via `App\Services\ImageService`. Sistem membaca file dengan _Intervention Image_, kemudian **menggambar ulang (re-encode)** menjadi format aman (`.webp`/`.png`/`.jpg`), lalu diberi nama acak baru. File asli (yang berpotensi disisipi script berbahaya) langsung diabaikan/tidak pernah disimpan.
2. **Impor Excel Pipa Langsung (Stream Piped)**: Fitur impor massal menggunakan `Maatwebsite\Excel`. File yang diunggah hanya dibaca isinya di memori tanpa pernah disimpan secara permanen di direktori server, menghilangkan risiko eksekusi file liar.

---

**Versi Arsitektur:** 3.1  
**Terakhir Diperbarui:** 2026-03-03
