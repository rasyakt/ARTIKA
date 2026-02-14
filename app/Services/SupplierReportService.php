<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\SupplierPreOrder;
use App\Models\SupplierPreOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierReportService
{
    /**
     * Get summary statistics for supplier pre-orders
     */
    public function getSummaryStats($startDate, $endDate)
    {
        $query = SupplierPreOrder::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_orders' => (clone $query)->count(),
            'pending_orders' => (clone $query)->where('status', 'pending')->count(),
            'received_orders' => (clone $query)->where('status', 'received')->count(),
            'cancelled_orders' => (clone $query)->where('status', 'cancelled')->count(),
            'total_spend' => (clone $query)->where('status', 'received')->sum('total_amount'),
        ];
    }

    /**
     * Get pre-order activity by supplier
     */
    public function getSuppliersPerformance($startDate, $endDate)
    {
        return Supplier::withCount([
            'preOrders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
            ->withSum([
                'preOrders' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', 'received');
                }
            ], 'total_amount')
            ->orderBy('pre_orders_sum_total_amount', 'desc')
            ->get();
    }

    /**
     * Get recently received pre-orders
     */
    public function getRecentReceived($startDate, $endDate, $limit = 10)
    {
        return SupplierPreOrder::with('supplier', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'received')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
