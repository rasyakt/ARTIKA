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
    public function getCashierAuditLogs($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return AuditLog::with('user.role')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('model_type', ['Transaction', 'User'])
            ->whereHas('user.role', function ($query) {
                $query->where('name', 'cashier');
            })
            ->latest()
            ->get();
    }

    /**
     * Get summary statistics for cashier operations.
     */
    public function getSummaryStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate]);

        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Payment method breakdown
        $paymentBreakdown = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        return [
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
            'cash_sales' => ($paymentBreakdown->get('Cash')->total ?? 0) + ($paymentBreakdown->get('tunai')->total ?? 0),
            'non_cash_sales' => ($paymentBreakdown->get('non-cash')->total ?? 0) + ($paymentBreakdown->get('non-tunai')->total ?? 0),
            'cash_count' => ($paymentBreakdown->get('Cash')->count ?? 0) + ($paymentBreakdown->get('tunai')->count ?? 0),
            'non_cash_count' => ($paymentBreakdown->get('non-cash')->count ?? 0) + ($paymentBreakdown->get('non-tunai')->count ?? 0),
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
            ->select('user_id', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(total_amount) as total_sales'))
            ->groupBy('user_id')
            ->orderByDesc('total_sales')
            ->get();
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions($startDate = null, $endDate = null, $limit = 20)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
