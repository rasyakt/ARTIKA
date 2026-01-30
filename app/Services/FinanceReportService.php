<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ReturnTransaction;
use App\Models\Expense;
use App\Models\SupplierPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceReportService
{
    /**
     * Get financial summary for a given date range.
     */
    public function getFinancialSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // 1. Gross Revenue (Total Sales)
        $grossRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        // 2. COGS (Cost of Goods Sold)
        // Calculated as items.quantity * products.cost_price
        $cogs = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'completed')
            ->select(DB::raw('SUM(transaction_items.quantity * products.cost_price) as total_cogs'))
            ->value('total_cogs') ?? 0;

        // 3. Gross Profit
        $grossProfit = $grossRevenue - $cogs;

        // 4. Returns & Refunds
        $totalReturns = ReturnTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_refund');

        // 5. Operating Expenses
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // 6. Stock Procurement (New)
        $totalProcurement = SupplierPurchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('total_price');

        // 7. Net Profit
        // Net Profit = Gross Profit - Returns - Expenses
        // Note: Stock procurement is usually not deducted from P&L net profit directly (it's in COGS),
        // but we track it for cash flow awareness.
        $netProfit = $grossProfit - $totalReturns - $totalExpenses;

        // 8. Profit Margin
        $profitMargin = $grossRevenue > 0 ? ($netProfit / $grossRevenue) * 100 : 0;

        return [
            'gross_revenue' => $grossRevenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'total_returns' => $totalReturns,
            'total_expenses' => $totalExpenses,
            'total_procurement' => $totalProcurement,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
        ];
    }

    /**
     * Get financial data grouped by day for the period.
     */
    public function getDailyFinanceData($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $dailyData = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        // We also need COGS per day
        $dailyCogs = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'completed')
            ->select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('SUM(transaction_items.quantity * products.cost_price) as cogs')
            )
            ->groupBy(DB::raw('DATE(transactions.created_at)'))
            ->get()
            ->keyBy('date');

        // 3. Expenses per day
        $dailyExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->select(
                DB::raw('date'),
                DB::raw('SUM(amount) as cost')
            )
            ->groupBy('date')
            ->get()
            ->keyBy(function ($item) {
                // Ensure key is Y-m-d string regardless of model casting
                return is_string($item->date) ? substr($item->date, 0, 10) : $item->date->format('Y-m-d');
            });

        // 4. Procurement per day
        $dailyProcurement = SupplierPurchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->select(
                DB::raw('purchase_date'),
                DB::raw('SUM(total_price) as cost')
            )
            ->groupBy('purchase_date')
            ->get()
            ->keyBy(function ($item) {
                return is_string($item->purchase_date) ? substr($item->purchase_date, 0, 10) : $item->purchase_date->format('Y-m-d');
            });

        $result = [];
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $revenue = floatval($dailyData->get($dateStr)->revenue ?? 0);
            $cogs = floatval($dailyCogs->get($dateStr)->cogs ?? 0);
            $expense = floatval($dailyExpenses->get($dateStr)->cost ?? 0);
            $procurement = floatval($dailyProcurement->get($dateStr)->cost ?? 0);

            $result[] = [
                'date' => $dateStr,
                'revenue' => $revenue,
                'cogs' => $cogs,
                'expenses' => $expense,
                'procurement' => $procurement,
                'profit' => $revenue - $cogs - $expense,
            ];
            $currentDate->addDay();
        }

        return $result;
    }
}
