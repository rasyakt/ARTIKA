<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()->role->name ?? 'user';
        if ($role === 'admin')
            return redirect()->route('admin.dashboard');
        if ($role === 'cashier')
            return redirect()->route('pos.index');
        if ($role === 'warehouse')
            return redirect()->route('warehouse.dashboard');
        return view('dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
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

        // Customer Management
        Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers');
        Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');
        Route::put('/customers/{id}', [\App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id}', [\App\Http\Controllers\CustomerController::class, 'destroy'])->name('customers.delete');

        // Reports
        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('reports');
    });

    // Cashier Routes (POS)
    Route::middleware(['role:cashier'])->prefix('pos')->name('pos.')->group(function () {
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
