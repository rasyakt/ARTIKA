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
            ->latest()
            ->limit(10)
            ->get();

        // Low Stock Alerts (quantity < 20)
        $lowStockProducts = Stock::with(['product.category'])
            ->where('quantity', '<', 20)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalTransactions',
            'totalProducts',
            // 'totalCustomers',
            'totalSuppliers',
            'recentSuppliers',
            'salesChartData',
            'topProducts',
            'recentTransactions',
            'lowStockProducts'
        ));
    }

    private function getSalesChartData()
    {
        // Daily data (last 7 days)
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyData['labels'][] = $date->format('D, M j');
            $dailyData['values'][] = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
        }

        // Weekly data (last 4 weeks)
        $weeklyData = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeklyData['labels'][] = 'Week ' . $weekStart->format('M j');
            $weeklyData['values'][] = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status', 'completed')
                ->sum('total_amount');
        }

        // Monthly data (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyData['labels'][] = $month->format('M Y');
            $monthlyData['values'][] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'completed')
                ->sum('total_amount');
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
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('barcode', 'ilike', "%{$search}%");
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
        ]);

        return DB::transaction(function () use ($request) {
            $product = Product::create($request->all());

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
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
}

