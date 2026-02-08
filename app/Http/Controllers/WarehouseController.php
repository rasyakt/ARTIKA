<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\AuditLog;
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
                $totalStock = $category->products->reduce(function ($carry, $product) {
                    return $carry + $product->stocks->sum('quantity');
                }, 0);
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
            'stock_id' => 'nullable|exists:stocks,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract,set',
            'reason' => 'nullable|string|max:255',
            'expired_at' => 'nullable|date',
            'batch_no' => 'nullable|string|max:100'
        ]);

        // If stock_id is provided, we target that specific batch
        if ($request->stock_id) {
            $stock = Stock::find($request->stock_id);
        } else {
            // Otherwise, find the latest general batch or most recently added
            $stock = Stock::where('product_id', $request->product_id)
                ->when(!$request->expired_at, function ($q) {
                    return $q->whereNull('expired_at');
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // If it's an 'add' and we have specific batch info, try to MERGE with existing batch first
        if ($request->type === 'add' && $request->batch_no) {
            $existingStock = Stock::where('product_id', $request->product_id)
                ->where('batch_no', $request->batch_no)
                ->first();

            if ($existingStock) {
                $quantityBefore = $existingStock->quantity;
                return DB::transaction(function () use ($request, $existingStock, $quantityBefore) {
                    $existingStock->quantity += $request->quantity;
                    // Update expiry if provided, otherwise keep existing
                    if ($request->expired_at) {
                        $existingStock->expired_at = $request->expired_at;
                    }
                    $existingStock->save();

                    $this->logStockMovement($existingStock, $request->quantity, 'in', $quantityBefore, $request->reason ?? 'Restock (Merged Batch)');
                    $this->logAudit($existingStock, $quantityBefore, $request->quantity, 'add');

                    return response()->json([
                        'success' => true,
                        'new_quantity' => $existingStock->quantity,
                        'message' => __('warehouse.stock_adjusted_successfully')
                    ]);
                });
            }
        }

        // If it's an 'add' and NO matching batch was found, or we have expiry info, create a NEW one
        if ($request->type === 'add' && ($request->expired_at || $request->batch_no || !$stock)) {
            $quantityBefore = 0;
            return DB::transaction(function () use ($request, $quantityBefore) {
                $stock = Stock::create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'expired_at' => $request->expired_at,
                    'batch_no' => $request->batch_no,
                    'min_stock' => 10 // Default
                ]);

                $this->logStockMovement($stock, $request->quantity, 'in', $quantityBefore, $request->reason);
                $this->logAudit($stock, 0, $request->quantity, 'add');

                return response()->json([
                    'success' => true,
                    'new_quantity' => $stock->quantity,
                    'message' => __('warehouse.stock_adjusted_successfully')
                ]);
            });
        }

        if (!$stock) {
            return response()->json(['success' => false, 'message' => 'Stock record not found'], 404);
        }

        $quantityBefore = $stock->quantity;

        return DB::transaction(function () use ($request, $stock, $quantityBefore) {
            switch ($request->type) {
                case 'add':
                    $stock->quantity += $request->quantity;
                    $quantityChange = $request->quantity;
                    $moveType = 'in';
                    break;
                case 'subtract':
                    $stock->quantity -= $request->quantity;
                    $quantityChange = -$request->quantity;
                    $moveType = 'out';
                    break;
                case 'set':
                    $quantityChange = $request->quantity - $stock->quantity;
                    $stock->quantity = $request->quantity;
                    $moveType = 'adjustment';
                    break;
            }

            $stock->save();

            $this->logStockMovement($stock, $quantityChange, $moveType, $quantityBefore, $request->reason);
            $this->logAudit($stock, $quantityBefore, $quantityChange, $request->type);

            return response()->json([
                'success' => true,
                'new_quantity' => $stock->quantity,
                'message' => __('warehouse.stock_adjusted_successfully')
            ]);
        });
    }

    public function destroyStock(Request $request, $id)
    {
        $stock = Stock::with('product')->findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        return DB::transaction(function () use ($stock, $request) {
            $quantityBefore = $stock->quantity;

            // Log movement as "scrap"
            $this->logStockMovement($stock, -$quantityBefore, 'out', $quantityBefore, 'Scrapped: ' . $request->reason);

            // Audit Log
            AuditLog::log(
                'stock_scrapped',
                'Stock',
                $stock->id,
                -$quantityBefore,
                0,
                ['reason' => $request->reason, 'batch' => $stock->batch_no],
                'Stock batch deleted for ' . $stock->product->name
            );

            $stock->delete();

            return response()->json([
                'success' => true,
                'message' => __('warehouse.stock_scrapped_successfully')
            ]);
        });
    }

    private function logStockMovement($stock, $quantityChange, $type, $quantityBefore, $reason = null)
    {
        StockMovement::create([
            'product_id' => $stock->product_id,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $stock->quantity,
            'quantity_change' => $quantityChange,
            'reason' => $reason ?? ($type === 'in' ? 'Restock/Stock In' : 'Manual adjustment'),
            'reference' => ($type === 'in' ? 'IN-' : ($type === 'out' ? 'OUT-' : 'ADJ-')) . date('YmdHis')
        ]);
    }

    private function logAudit($stock, $quantityBefore, $quantityChange, $type)
    {
        AuditLog::log(
            'stock_adjusted',
            'Stock',
            $stock->id,
            $quantityChange,
            $stock->quantity,
            ['type' => $type, 'change' => $quantityChange],
            'Manual adjustment for ' . $stock->product->name
        );
    }
}

