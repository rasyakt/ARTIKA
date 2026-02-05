<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashierReportService
{
    /**
     * Get cashier audit logs for the period (only cashier role).
     */
    public function getCashierAuditLogs($startDate = null, $endDate = null, $search = null, $action = null, $perPage = null, $pageName = 'page')
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $query = AuditLog::with('user.role')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('model_type', ['Transaction', 'User'])
            ->whereHas('user.role', function ($query) {
                $query->where('name', 'cashier');
            });

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nis', 'ilike', "%{$search}%")
                    ->orWhere('username', 'ilike', "%{$search}%")
                    ->orWhere('name', 'ilike', "%{$search}%");
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        $query->latest();

        return $perPage ? $query->paginate($perPage, ['*'], $pageName) : $query->get();
    }

    /**
     * Get summary statistics for cashier operations.
     */
    public function getSummaryStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Payment method breakdown
        $paymentBreakdown = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtolower($item->payment_method) => $item];
            });

        $cashKeys = ['cash', 'tunai'];
        $nonCashKeys = ['non-cash', 'non-tunai', 'qris', 'transfer', 'debit'];

        $cashSales = 0;
        $cashCount = 0;
        foreach ($cashKeys as $key) {
            $cashSales += $paymentBreakdown->get($key)->total ?? 0;
            $cashCount += $paymentBreakdown->get($key)->count ?? 0;
        }

        $nonCashSales = 0;
        $nonCashCount = 0;
        foreach ($nonCashKeys as $key) {
            $nonCashSales += $paymentBreakdown->get($key)->total ?? 0;
            $nonCashCount += $paymentBreakdown->get($key)->count ?? 0;
        }

        return [
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
            'cash_sales' => $cashSales,
            'non_cash_sales' => $nonCashSales,
            'cash_count' => $cashCount,
            'non_cash_count' => $nonCashCount,
        ];
    }

    /**
     * Get payment method breakdown.
     */
    public function getPaymentMethodBreakdown($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
    }

    /**
     * Get top selling products in the period.
     */
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.barcode',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.barcode')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Get transaction breakdown by cashier/user.
     */
    public function getTransactionsByUser($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('user_id', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(total_amount) as total_sales'))
            ->groupBy('user_id')
            ->orderByDesc('total_sales')
            ->get();
    }

    /**
     * Get sales breakdown by category.
     */
    public function getSalesByCategory($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'completed')
            ->select(
                'categories.name',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    /**
     * Get discount summary for the period.
     */
    public function getDiscountSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $stats = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->where('discount', '>', 0)
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(discount) as total_discount'),
                DB::raw('SUM(total_amount) as total_revenue_after_discount')
            )
            ->first();

        return [
            'count' => $stats->count ?? 0,
            'total_discount' => $stats->total_discount ?? 0,
            'total_revenue' => $stats->total_revenue_after_discount ?? 0
        ];
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions($startDate = null, $endDate = null, $limit = 20, $search = null, $perPage = null, $pageName = 'page')
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $query = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nis', 'ilike', "%{$search}%")
                    ->orWhere('username', 'ilike', "%{$search}%")
                    ->orWhere('name', 'ilike', "%{$search}%");
            });
        }

        $query->latest();

        if ($perPage) {
            return $query->paginate($perPage, ['*'], $pageName);
        }

        return $query->limit($limit)->get();
    }
}
