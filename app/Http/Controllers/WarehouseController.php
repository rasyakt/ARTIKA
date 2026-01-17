<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        // Low Stock Alerts (quantity < 20)
        $lowStockItems = Stock::with(['product.category'])
            ->where('quantity', '<', 20)
            ->orderBy('quantity', 'asc')
            ->get();

        // Total Products
        $totalProducts = Product::count();

        // Total Stock Value
        $totalStockValue = Stock::join('products', 'stocks.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(stocks.quantity * products.cost_price) as total_value'))
            ->value('total_value') ?? 0;

        // Stock by Category
        $stockByCategory = Category::withCount(['products'])
            ->with([
                'products' => function ($query) {
                    $query->with('stocks');
                }
            ])
            ->get()
            ->map(function ($category) {
                $totalStock = $category->products->sum(function ($product) {
                    return $product->stocks->sum('quantity');
                });
                return [
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                    'total_stock' => $totalStock
                ];
            });

        // Recent Stock Movements (simulated - you can create a stock_movements table later)
        $recentProducts = Product::with(['stocks', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        return view('warehouse.dashboard', compact(
            'lowStockItems',
            'totalProducts',
            'totalStockValue',
            'stockByCategory',
            'recentProducts'
        ));
    }

    public function lowStock()
    {
        // Low Stock Alerts (quantity < 20)
        $lowStockItems = Stock::with(['product.category'])
            ->where('quantity', '<', 20)
            ->orderBy('quantity', 'asc')
            ->paginate(10);

        // Statistics
        $criticalCount = Stock::where('quantity', '<', 10)->count();
        $lowCount = Stock::where('quantity', '>=', 10)->where('quantity', '<', 20)->count();
        $totalAlerts = $lowStockItems->total();

        return view('warehouse.low-stock', compact('lowStockItems', 'criticalCount', 'lowCount', 'totalAlerts'));
    }

    public function stockManagement()
    {
        $stocks = Stock::with(['product.category'])
            ->orderBy('quantity', 'asc')
            ->paginate(10);

        return view('warehouse.stock', compact('stocks'));
    }

    public function stockMovements()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->latest()
            ->paginate(10);

        // Statistics for today
        $today = now()->startOfDay();
        $stockInToday = StockMovement::where('type', 'in')
            ->where('created_at', '>=', $today)
            ->sum('quantity_change');

        $stockOutToday = StockMovement::where('type', 'out')
            ->where('created_at', '>=', $today)
            ->sum('quantity_change');

        $adjustmentsToday = StockMovement::where('type', 'adjustment')
            ->where('created_at', '>=', $today)
            ->count();

        $totalMovements = StockMovement::count();

        // Rename for the view
        $recentMovements = $movements;

        return view('warehouse.stock-movements', compact(
            'movements',
            'recentMovements',
            'stockInToday',
            'stockOutToday',
            'adjustmentsToday',
            'totalMovements'
        ));
    }

    public function adjustStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract,set',
            'reason' => 'nullable|string|max:255'
        ]);

        $stock = Stock::where('product_id', $request->product_id)
            ->first();

        if (!$stock) {
            return response()->json(['success' => false, 'message' => 'Stock not found'], 404);
        }

        $quantityBefore = $stock->quantity;

        switch ($request->type) {
            case 'add':
                $stock->quantity += $request->quantity;
                $quantityChange = $request->quantity;
                break;
            case 'subtract':
                $stock->quantity -= $request->quantity;
                $quantityChange = -$request->quantity;
                break;
            case 'set':
                $quantityChange = $request->quantity - $stock->quantity;
                $stock->quantity = $request->quantity;
                break;
        }

        $stock->save();

        // Log the movement
        StockMovement::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'type' => 'adjustment',
            'quantity_before' => $quantityBefore,
            'quantity_after' => $stock->quantity,
            'quantity_change' => $quantityChange,
            'reason' => $request->reason ?? 'Manual adjustment',
            'reference' => 'ADJ-' . date('YmdHis')
        ]);

        return response()->json([
            'success' => true,
            'new_quantity' => $stock->quantity,
            'message' => 'Stock adjusted successfully'
        ]);
    }
}

