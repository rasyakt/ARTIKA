<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Stock;
use Carbon\Carbon;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class WarehouseReportService
{
    /**
     * Get audit logs related to warehouse operations (Products, Stocks, Suppliers, Categories).
     */
    public function getWarehouseAuditLogs($startDate = null, $endDate = null, $perPage = null, $pageName = 'page')
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $query = AuditLog::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('model_type', ['Product', 'Stock', 'StockMovement', 'Category', 'Supplier'])
            ->latest();

        return $perPage ? $query->paginate($perPage, ['*'], $pageName) : $query->get();
    }
    /**
     * Get summary statistics for a given date range.
     */
    public function getSummaryStats($startDate = null, $endDate = null, $filters = [])
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $stockStatus = $filters['stock_status'] ?? null;

        // Total Valuation (Current Stock * Cost Price)
        $valuationQuery = Product::join('stocks', 'products.id', '=', 'stocks.product_id');

        if ($search) {
            $valuationQuery->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.barcode', 'like', "%{$search}%");
            });
        }
        if ($categoryId) {
            $valuationQuery->where('products.category_id', $categoryId);
        }
        if ($stockStatus) {
            if ($stockStatus === 'low') {
                $valuationQuery->whereColumn('stocks.quantity', '<=', 'stocks.min_stock')
                    ->where('stocks.quantity', '>', 0);
            } elseif ($stockStatus === 'out') {
                $valuationQuery->where('stocks.quantity', '<=', 0);
            } elseif ($stockStatus === 'available') {
                $valuationQuery->where('stocks.quantity', '>', 'stocks.min_stock');
            }
        }

        $totalValuation = $valuationQuery->select(DB::raw('SUM(stocks.quantity * products.cost_price) as total_value'))
            ->value('total_value') ?? 0;

        $totalItemsQuery = Stock::query();
        if ($search || $categoryId || $stockStatus) {
            $totalItemsQuery->join('products', 'products.id', '=', 'stocks.product_id');
            if ($search) {
                $totalItemsQuery->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            }
            if ($categoryId) {
                $totalItemsQuery->where('products.category_id', $categoryId);
            }
            if ($stockStatus) {
                if ($stockStatus === 'low') {
                    $totalItemsQuery->whereColumn('stocks.quantity', '<=', 'stocks.min_stock')
                        ->where('stocks.quantity', '>', 0);
                } elseif ($stockStatus === 'out') {
                    $totalItemsQuery->where('stocks.quantity', '<=', 0);
                } elseif ($stockStatus === 'available') {
                    $totalItemsQuery->where('stocks.quantity', '>', 'stocks.min_stock');
                }
            }
        }
        $totalItems = $totalItemsQuery->sum('stocks.quantity');

        $lowStockCount = $this->getLowStockItems(null, 'page', $filters)->count();

        // Movement counts in period
        $movementsQuery = StockMovement::whereBetween('stock_movements.created_at', [$startDate, $endDate]);

        if ($search || $categoryId) {
            $movementsQuery->join('products', 'products.id', '=', 'stock_movements.product_id');
            if ($search) {
                $movementsQuery->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            }
            if ($categoryId) {
                $movementsQuery->where('products.category_id', $categoryId);
            }
        }

        $movements = $movementsQuery->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(ABS(quantity_change)) as total_quantity'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return [
            'total_valuation' => $totalValuation,
            'total_items' => $totalItems,
            'low_stock_count' => $lowStockCount,
            'movements_in' => $movements->get('in')->total_quantity ?? 0,
            'movements_out' => $movements->get('out')->total_quantity ?? 0,
            'movements_adjustment' => $movements->get('adjustment')->total_quantity ?? 0,
        ];
    }

    /**
     * Get stock movements filtered by date range.
     */
    public function getStockMovements($startDate = null, $endDate = null, $type = null, $perPage = null, $pageName = 'page', $filters = [])
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;

        $query = StockMovement::with(['product', 'user'])
            ->whereBetween('stock_movements.created_at', [$startDate, $endDate]);

        if ($type) {
            $query->where('stock_movements.type', $type);
        }

        if ($search || $categoryId) {
            $query->join('products', 'products.id', '=', 'stock_movements.product_id')
                ->select('stock_movements.*');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            }

            if ($categoryId) {
                $query->where('products.category_id', $categoryId);
            }
        }

        return $perPage ? $query->latest('stock_movements.created_at')->paginate($perPage, ['*'], $pageName) : $query->latest('stock_movements.created_at')->get();
    }

    /**
     * Get items that are below their minimum stock level.
     */
    public function getLowStockItems($perPage = null, $pageName = 'page', $filters = [])
    {
        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $stockStatus = $filters['stock_status'] ?? null;

        $query = Product::join('stocks', 'products.id', '=', 'stocks.product_id');

        if ($stockStatus === 'out') {
            $query->where('stocks.quantity', '<=', 0);
        } elseif ($stockStatus === 'available') {
            $query->where('stocks.quantity', '>', 'stocks.min_stock');
        } else {
            // Default to low stock filter if no specific status or if 'low' is selected
            $query->whereColumn('stocks.quantity', '<=', 'stocks.min_stock')
                ->where('stocks.quantity', '>', 0);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.barcode', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        $query->select('products.*', 'stocks.quantity as current_stock', 'stocks.min_stock');

        return $perPage ? $query->paginate($perPage, ['*'], $pageName) : $query->get();
    }

    /**
     * Get top moving items (most frequent movements).
     */
    public function getTopMovingItems($startDate = null, $endDate = null, $limit = 5, $filters = [])
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;

        $query = StockMovement::whereBetween('stock_movements.created_at', [$startDate, $endDate]);

        if ($search || $categoryId) {
            $query->join('products', 'products.id', '=', 'stock_movements.product_id');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            }
            if ($categoryId) {
                $query->where('products.category_id', $categoryId);
            }
        }

        return $query->select('stock_movements.product_id', DB::raw('COUNT(*) as total_movements'), DB::raw('SUM(ABS(quantity_change)) as total_quantity'))
            ->groupBy('stock_movements.product_id')
            ->orderByDesc('total_quantity')
            ->take($limit)
            ->with('product')
            ->get();
    }
}
