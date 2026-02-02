<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login/admin', [AuthController::class, 'showAdminLoginForm'])->name('login.admin');
Route::get('/login/warehouse', [AuthController::class, 'showWarehouseLoginForm'])->name('login.warehouse');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()->role->name ?? 'user';
        if ($role === 'admin' || $role === 'manager')
            return redirect()->route('admin.dashboard');
        if ($role === 'cashier')
            return redirect()->route('pos.index');
        if ($role === 'warehouse')
            return redirect()->route('warehouse.dashboard');
        return view('dashboard');
    })->name('dashboard');

    // Admin & Manager Routes
    Route::middleware(['role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

        // Product Management
        Route::get('/products', [\App\Http\Controllers\AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [\App\Http\Controllers\AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{id}/edit', [\App\Http\Controllers\AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{id}', [\App\Http\Controllers\AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('products.delete');

        // Category Management
        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.delete');

        // User Management
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.delete');

        // Supplier Management
        Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers');
        Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'show'])->name('suppliers.show');
        Route::put('/suppliers/{id}', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{id}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.delete');
        Route::get('/suppliers/{supplier}/pdf', [\App\Http\Controllers\SupplierController::class, 'exportPdf'])->name('suppliers.pdf');
        Route::get('/suppliers/{supplier}/csv', [\App\Http\Controllers\SupplierController::class, 'exportCsv'])->name('suppliers.csv');

        // Supplier Purchases
        Route::post('/supplier-purchases', [\App\Http\Controllers\SupplierPurchaseController::class, 'store'])->name('supplier-purchases.store');

        // Expense Management
        Route::get('/expenses', [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
        Route::put('/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.delete');

        // Expense Category Management
        Route::get('/expense-categories', [\App\Http\Controllers\ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
        Route::post('/expense-categories', [\App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::put('/expense-categories/{expenseCategory}', [\App\Http\Controllers\ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::delete('/expense-categories/{expenseCategory}', [\App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.delete');

        // Reports
        // Reports Hub
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportsHubController::class, 'index'])->name('reports');
        Route::get('/reports/print-all', [\App\Http\Controllers\Admin\ReportsHubController::class, 'printAll'])->name('reports.print-all');

        // Warehouse Reports
        Route::get('/reports/warehouse', [\App\Http\Controllers\Admin\WarehouseReportController::class, 'index'])->name('reports.warehouse');
        Route::get('/reports/warehouse/export', [\App\Http\Controllers\Admin\WarehouseReportController::class, 'export'])->name('reports.warehouse.export');

        // Cashier Reports
        Route::get('/reports/cashier', [\App\Http\Controllers\Admin\CashierReportController::class, 'index'])->name('reports.cashier');
        Route::get('/reports/cashier/export', [\App\Http\Controllers\Admin\CashierReportController::class, 'export'])->name('reports.cashier.export');
        Route::post('/reports/cashier/rollback/{id}', [\App\Http\Controllers\Admin\CashierReportController::class, 'rollback'])->name('reports.cashier.rollback');
        Route::get('/reports/cashier/items/{id}', [\App\Http\Controllers\Admin\CashierReportController::class, 'getTransactionItems'])->name('reports.cashier.items');
        Route::put('/reports/cashier/update/{id}', [\App\Http\Controllers\Admin\CashierReportController::class, 'update'])->name('reports.cashier.update');
        Route::delete('/reports/cashier/delete/{id}', [\App\Http\Controllers\Admin\CashierReportController::class, 'destroy'])->name('reports.cashier.delete');
        Route::get('/reports/cashier/receipt/{id}', [\App\Http\Controllers\Admin\CashierReportController::class, 'printReceipt'])->name('reports.cashier.receipt');

        // Finance Reports
        Route::get('/reports/finance', [\App\Http\Controllers\Admin\FinanceReportController::class, 'index'])->name('reports.finance');
        Route::get('/reports/finance/export', [\App\Http\Controllers\Admin\FinanceReportController::class, 'export'])->name('reports.finance.export');

        // Audit Logs
        Route::get('/audit', [\App\Http\Controllers\AuditController::class, 'index'])->name('audit.index');
        Route::get('/audit/export', [\App\Http\Controllers\AuditController::class, 'export'])->name('audit.export');
        Route::post('/audit/clear', [\App\Http\Controllers\AuditController::class, 'clear'])->name('audit.clear');
    });

    // Cashier Routes (POS)
    Route::middleware(['role:cashier'])->prefix('pos')->name('pos.')->group(function () {
        Route::get('/logs', [\App\Http\Controllers\PosController::class, 'logs'])->name('logs');
        Route::get('/history', [\App\Http\Controllers\PosController::class, 'history'])->name('history');
        Route::get('/', [\App\Http\Controllers\PosController::class, 'index'])->name('index');
        Route::get('/scanner', [\App\Http\Controllers\PosController::class, 'scanner'])->name('scanner');
        Route::post('/checkout', [\App\Http\Controllers\PosController::class, 'store'])->name('checkout');
        Route::post('/hold', [\App\Http\Controllers\PosController::class, 'holdTransaction'])->name('hold');
        Route::get('/held', [\App\Http\Controllers\PosController::class, 'getHeldTransactions'])->name('held.index');
        Route::get('/held/{id}/resume', [\App\Http\Controllers\PosController::class, 'resumeHeldTransaction'])->name('held.resume');
        Route::delete('/held/{id}', [\App\Http\Controllers\PosController::class, 'deleteHeldTransaction'])->name('held.delete');
        Route::get('/receipt/{id}', [\App\Http\Controllers\PosController::class, 'printReceipt'])->name('receipt');
    });

    // Warehouse Routes
    Route::middleware(['role:warehouse'])->prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\WarehouseController::class, 'index'])->name('dashboard');
        Route::get('/stock', [\App\Http\Controllers\WarehouseController::class, 'stockManagement'])->name('stock');
        Route::get('/low-stock', [\App\Http\Controllers\WarehouseController::class, 'lowStock'])->name('low-stock');
        Route::get('/stock-movements', [\App\Http\Controllers\WarehouseController::class, 'stockMovements'])->name('stock-movements');
        Route::post('/stock/adjust', [\App\Http\Controllers\WarehouseController::class, 'adjustStock'])->name('stock.adjust');
    });
});

// Language switching route (accessible to all)
Route::get('/language/{lang}', [\App\Http\Controllers\LanguageController::class, 'change'])
    ->name('language.change')
    ->where('lang', 'id|en');

