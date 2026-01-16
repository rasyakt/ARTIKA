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
    public function getWarehouseAuditLogs($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return AuditLog::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('model_type', ['Product', 'Stock', 'StockMovement', 'Category', 'Supplier'])
            ->latest()
            ->get();
    }
    /**
     * Get summary statistics for a given date range.
     */
    public function getSummaryStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Total Valuation (Current Stock * Cost Price)
        // Note: This is an approximation based on current stock levels and current cost price.
        // For historical valuation, we would need snapshotted data which we might not have fully.
        // We will show CURRENT valuation as a KPI.
        $totalValuation = Product::join('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(DB::raw('SUM(stocks.quantity * products.cost_price) as total_value'))
            ->value('total_value') ?? 0;

        $totalItems = Stock::sum('quantity');
        $lowStockCount = $this->getLowStockItems()->count();

        // Movement counts in period
        $movements = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(ABS(quantity_change)) as total_quantity'))
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
    public function getStockMovements($startDate = null, $endDate = null, $type = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $query = StockMovement::with(['product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    /**
     * Get items that are below their minimum stock level.
     */
    public function getLowStockItems()
    {
        return Product::join('stocks', 'products.id', '=', 'stocks.product_id')
            ->whereColumn('stocks.quantity', '<=', 'stocks.min_stock')
            ->select('products.*', 'stocks.quantity as current_stock', 'stocks.min_stock')
            ->get();
    }

    /**
     * Get top moving items (most frequent movements).
     */
    public function getTopMovingItems($startDate = null, $endDate = null, $limit = 5)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->select('product_id', DB::raw('COUNT(*) as total_movements'), DB::raw('SUM(ABS(quantity_change)) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take($limit)
            ->with('product')
            ->get();
    }
}
