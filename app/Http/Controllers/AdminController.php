<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductTemplateExport;

class AdminController extends Controller
{
    public function index()
    {
        // Statistics
        $totalSales = Transaction::where('status', 'completed')->sum('total_amount');
        $totalTransactions = Transaction::where('status', 'completed')->count();
        $totalProducts = Product::count();

        // Supplier metrics (fallback to 0 if Supplier model/table doesn't exist yet)
        try {
            $totalSuppliers = \App\Models\Supplier::count();
            $recentSuppliers = \App\Models\Supplier::latest()->limit(5)->get();
        } catch (\Exception $e) {
            $totalSuppliers = 0;
            $recentSuppliers = collect();
        }

        // Sales Chart Data
        $salesChartData = $this->getSalesChartData();

        // Top Products (by revenue)
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->with('product')
            ->get()
            ->filter(function ($item) {
                return $item->product !== null;
            })
            ->map(function ($item) {
                return (object) [
                    'name' => $item->product->name,
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue
                ];
            });

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
            ->where('status', 'completed')
            ->latest()
            ->limit(10)
            ->get();

        // Low Stock Alerts (quantity < 20)
        $lowStockProducts = Stock::with(['product.category'])
            ->where('quantity', '>', 0)
            ->where('quantity', '<', 20)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();

        // Expiring Soon (within 30 days)
        $expiringSoonProducts = Stock::with(['product.category'])
            ->where('quantity', '>', 0)
            ->whereNotNull('expired_at')
            ->where('expired_at', '>', Carbon::today())
            ->where('expired_at', '<=', Carbon::today()->addDays(30))
            ->orderBy('expired_at', 'asc')
            ->limit(10)
            ->get();

        // Expired Products
        $expiredProducts = Stock::with(['product.category'])
            ->where('quantity', '>', 0)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', Carbon::today())
            ->orderBy('expired_at', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalTransactions',
            'totalProducts',
            'totalSuppliers',
            'recentSuppliers',
            'salesChartData',
            'topProducts',
            'recentTransactions',
            'lowStockProducts',
            'expiringSoonProducts',
            'expiredProducts'
        ));
    }

    private function getSalesChartData()
    {
        // Daily data (last 7 days) — 1 query instead of 7
        $dailyData = ['labels' => [], 'values' => []];
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $dailyResults = Transaction::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as sale_date, COALESCE(SUM(total_amount), 0) as total')
            ->groupBy('sale_date')
            ->pluck('total', 'sale_date');

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyData['labels'][] = $date->format('D, M j');
            $dailyData['values'][] = (float) ($dailyResults[$date->toDateString()] ?? 0);
        }

        // Weekly data (last 4 weeks) — 1 query instead of 4
        $weeklyData = ['labels' => [], 'values' => []];
        $weekStart = Carbon::now()->subWeeks(3)->startOfWeek();

        $weeklyResults = Transaction::where('created_at', '>=', $weekStart)
            ->where('status', 'completed')
            ->selectRaw("DATE_FORMAT(created_at, '%x-%v') as sale_week, COALESCE(SUM(total_amount), 0) as total")
            ->groupBy('sale_week')
            ->pluck('total', 'sale_week');

        for ($i = 3; $i >= 0; $i--) {
            $ws = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekKey = $ws->format('o-W');
            $weeklyData['labels'][] = 'Week ' . $ws->format('M j');
            $weeklyData['values'][] = (float) ($weeklyResults[$weekKey] ?? 0);
        }

        // Monthly data (last 6 months) — 1 query instead of 6
        $monthlyData = ['labels' => [], 'values' => []];
        $monthStart = Carbon::now()->subMonths(5)->startOfMonth();

        $monthlyResults = Transaction::where('created_at', '>=', $monthStart)
            ->where('status', 'completed')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as sale_month, COALESCE(SUM(total_amount), 0) as total")
            ->groupBy('sale_month')
            ->pluck('total', 'sale_month');

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthlyData['labels'][] = $month->format('M Y');
            $monthlyData['values'][] = (float) ($monthlyResults[$monthKey] ?? 0);
        }

        return [
            'daily' => $dailyData,
            'weekly' => $weeklyData,
            'monthly' => $monthlyData
        ];
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'stocks']);

        // Search Filter (Name or Barcode)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = \App\Models\Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:png|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            $data = $request->only(['barcode', 'name', 'category_id', 'price', 'cost_price', 'description']);

            if ($request->hasFile('image')) {
                $imageService = app(\App\Services\ImageService::class);
                $data['image'] = $imageService->compress(
                    $request->file('image'),
                    'uploads/products'
                );
            }

            $product = Product::create($data);

            // Create initial stock
            Stock::create([
                'product_id' => $product->id,
                'quantity' => 0
            ]);

            // Log initial movement
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity_before' => 0,
                'quantity_after' => 0,
                'quantity_change' => 0,
                'reason' => 'Product Created',
                'reference' => 'NEW-' . $product->barcode
            ]);

            return redirect()->route('admin.products')->with('success', 'Product created successfully!');
        });
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'barcode' => 'required|unique:products,barcode,' . $id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:png|max:2048',
        ]);

        $data = $request->only(['barcode', 'name', 'category_id', 'price', 'cost_price', 'description']);

        if ($request->hasFile('image')) {
            $imageService = app(\App\Services\ImageService::class);
            $data['image'] = $imageService->compress(
                $request->file('image'),
                'uploads/products'
            );
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    /**
     * Download the Excel template for importing products.
     */
    public function downloadProductTemplate()
    {
        return Excel::download(new ProductTemplateExport, 'Template_Import_Produk.xlsx');
    }

    /**
     * Handle the Excel file import for products.
     */
    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // Max 5MB
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()->route('admin.products')->with('success', 'Berhasil mengimpor data produk secara massal dari file Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal import, format file salah pada baris: ';

            foreach ($failures as $failure) {
                $errorMsg .= $failure->row() . ' (' . implode(', ', $failure->errors()) . ')<br>';
            }

            return redirect()->route('admin.products')->with('error', $errorMsg);

        } catch (\Exception $e) {
            return redirect()->route('admin.products')->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
    }
}

