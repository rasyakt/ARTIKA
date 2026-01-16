# Panduan Migrasi: Menghapus Branch Feature

## Ringkasan Perubahan
Semua referensi ke branch telah dihapus dari aplikasi ARTIKA POS. Fitur ini tidak digunakan dan telah dibuang sepenuhnya.

## File-File yang Diubah

### Models
- **app/Models/User.php**: Dihapus `branch_id` dari `$fillable` dan method `branch()`
- **app/Models/Transaction.php**: Dihapus `branch_id` dari `$fillable`, method `branch()`, dan scope `scopeByBranch()`
- **app/Models/Stock.php**: Dihapus `branch_id` dari `$fillable` dan method `branch()`
- **app/Models/StockMovement.php**: Dihapus `branch_id` dari `$fillable` dan method `branch()`
- **app/Models/AuditLog.php**: Dihapus `branch_id` dari `$fillable`, method `branch()`, scope `scopeByBranch()`, dan dari method `log()`
- **app/Models/HeldTransaction.php**: Dihapus `branch_id` dari `$fillable` dan method `branch()`
- **app/Models/ReturnTransaction.php**: Dihapus `branch_id` dari `$fillable` dan method `branch()`

### Controllers
- **app/Http/Controllers/UserController.php**: 
  - Dihapus import `Branch`
  - Dihapus `$branches` dari `index()`
  - Dihapus validasi dan assignment `branch_id` dari `store()` dan `update()`
  
- **app/Http/Controllers/AdminController.php**: Dihapus logic pembuatan stock untuk semua branches di `storeProduct()`

- **app/Http/Controllers/PosController.php**: 
  - Dihapus `branch_id` dari data transaction di `store()`
  - Dihapus `branch_id` dari held transaction di `holdTransaction()`
  - Dihapus `branch` dari eager loading di `printReceipt()`

- **app/Http/Controllers/WarehouseController.php**:
  - Dihapus `$branchId` variable dari `index()`
  - Dihapus `branch` dari eager loading di semua methods
  - Dihapus validasi dan filter `branch_id` dari `adjustStock()`

- **app/Http/Controllers/AuditController.php**:
  - Dihapus import `Branch`
  - Dihapus filter `branch_id` dari `index()`, `downloadPdf()`, dan `exportCsv()`
  - Dihapus `branch` dari eager loading
  - Dihapus kolom "Branch" dari CSV export

### Services
- **app/Services/TransactionService.php**: Dihapus `branch_id` dari Journal entries di `processTransaction()`

### Views
- **resources/views/dashboard.blade.php**: Dihapus display branch dari welcome message
- **resources/views/admin/users/index.blade.php**: 
  - Dihapus kolom Branch dari user table
  - Dihapus Branch select field dari form create/edit
  - Dihapus assignment `branch_id` dari JavaScript editUser()

- **resources/views/admin/reports/index.blade.php**: Dihapus Branch filter dropdown
- **resources/views/pos/receipt.blade.php**: Dihapus referensi `$transaction->branch->address`
- **resources/views/admin/audit/pdf.blade.php**: Dihapus `$branch->name` dari title
- **resources/views/warehouse/low-stock.blade.php**: 
  - Dihapus kolom Branch dari table header dan data
  
- **resources/views/warehouse/stock.blade.php**: 
  - Dihapus kolom Branch dari table header dan data
  
- **resources/views/warehouse/stock-movements.blade.php**: Dihapus Branch display dari movement details
- **resources/views/warehouse/dashboard.blade.php**: Dihapus kolom Branch dari low stock alerts table

### Database
- **database/seeders/DatabaseSeeder.php**: 
  - Dihapus pembuatan Branch records
  - Dihapus `branch_id` assignment dari User seeds
  - Dihapus `branch_id` dari Stock seeds

### Migration
- **database/migrations/2026_01_15_000001_drop_branch_tables_and_columns.php**: 
  Baru dibuat untuk menghapus foreign key constraints dan kolom branch_id dari semua tables

## Langkah Migrasi

### 1. Backup Database
```bash
# Backup database Anda sebelum melakukan migrasi
mysqldump -u root -p artika_db > artika_backup_2026_01_15.sql
```

### 2. Jalankan Migration
```bash
php artisan migrate
```

Migration ini akan:
- Menghapus foreign key constraints untuk `branch_id` dari semua tables
- Menghapus kolom `branch_id` dari: users, stocks, stock_movements, transactions, audit_logs, held_transactions, returns, journals
- Menghapus table `branches`

### 3. Fresh Seeder (Optional)
Jika ingin refresh database dengan seeder baru:
```bash
php artisan migrate:fresh --seed
```

## Catatan Penting

⚠️ **Peringatan**: Operasi ini tidak dapat dibalikkan tanpa restore dari backup. Pastikan Anda:
1. Memiliki backup database sebelum menjalankan migration
2. Telah menguji aplikasi di development environment terlebih dahulu
3. Menginformasikan semua pengguna tentang perubahan ini

## Data yang Hilang

Setelah migrasi:
- Table `branches` akan dihapus sepenuhnya
- Semua data branch yang ada akan hilang
- Semua referensi user ke branch akan dihapus
- Filter branch di reports dan audit logs akan hilang

## Verifikasi Setelah Migrasi

Setelah migration, periksa:
1. Users dapat dibuat tanpa error
2. Transactions dapat diproses tanpa error
3. Stock dapat diatur tanpa error
4. Audit logs berfungsi dengan baik
5. Tidak ada error 500 di logs

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log
```

## Rollback (Jika Diperlukan)

Jika ada masalah:
1. Restore dari backup database
2. Jangan gunakan `php artisan migrate:rollback` karena down() method migration ini tidak tersedia

```bash
# Restore dari backup
mysql -u root -p artika_db < artika_backup_2026_01_15.sql
```

---
**Tanggal**: 15 Januari 2026
**Dibuat oleh**: Migration System
