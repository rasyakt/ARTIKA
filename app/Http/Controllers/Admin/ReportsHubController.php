<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WarehouseReportService;
use App\Services\CashierReportService;
use App\Services\FinanceReportService;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsHubController extends Controller
{
    protected $warehouseService;
    protected $cashierService;
    protected $financeService;

    public function __construct(
        WarehouseReportService $warehouseService,
        CashierReportService $cashierService,
        FinanceReportService $financeService
    ) {
        $this->warehouseService = $warehouseService;
        $this->cashierService = $cashierService;
        $this->financeService = $financeService;
    }

    /**
     * Display reports hub dashboard
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Print all reports consolidated
     */
    public function printAll(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'today':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today();
                    break;
                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
                case 'month':
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
            }
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Warehouse Report Data
        $warehouseSummary = $this->warehouseService->getSummaryStats($startDate, $endDate);
        $topMovers = $this->warehouseService->getTopMovingItems($startDate, $endDate);
        $lowStockItems = $this->warehouseService->getLowStockItems();
        $stockMovements = $this->warehouseService->getStockMovements($startDate, $endDate)->take(10);

        // Cashier Report Data
        $cashierSummary = $this->cashierService->getSummaryStats($startDate, $endDate);
        $topProducts = $this->cashierService->getTopSellingProducts($startDate, $endDate);
        $cashierPerformance = $this->cashierService->getTransactionsByUser($startDate, $endDate);
        $recentTransactions = $this->cashierService->getRecentTransactions($startDate, $endDate, 10);

        // Audit Logs
        $auditLogs = AuditLog::with('user.role')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(20)
            ->get();

        // Finance Report Data
        $financeSummary = $this->financeService->getFinancialSummary($startDate, $endDate);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.print-all', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'warehouseSummary' => $warehouseSummary,
                'topMovers' => $topMovers,
                'lowStockItems' => $lowStockItems,
                'stockMovements' => $stockMovements,
                'cashierSummary' => $cashierSummary,
                'topProducts' => $topProducts,
                'cashierPerformance' => $cashierPerformance,
                'recentTransactions' => $recentTransactions,
                'auditLogs' => $auditLogs,
                'financeSummary' => $financeSummary,
                'isPdf' => true
            ]);
            return $pdf->download('full-store-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        if ($request->input('format') === 'csv') {
            $filename = 'full-store-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($startDate, $endDate, $warehouseSummary, $topMovers, $lowStockItems, $cashierSummary, $topProducts, $cashierPerformance, $financeSummary, $auditLogs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['FULL STORE CONSOLIDATED REPORT', $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
                fputcsv($file, []);

                // FINANCE SECTION
                fputcsv($file, ['1. FINANCIAL SUMMARY']);
                fputcsv($file, ['Gross Revenue', 'Rp ' . number_format($financeSummary['gross_revenue'], 0, ',', '.')]);
                fputcsv($file, ['COGS', 'Rp ' . number_format($financeSummary['cogs'], 0, ',', '.')]);
                fputcsv($file, ['Gross Profit', 'Rp ' . number_format($financeSummary['gross_profit'], 0, ',', '.')]);
                fputcsv($file, ['Operational Expenses', 'Rp ' . number_format($financeSummary['total_expenses'], 0, ',', '.')]);
                fputcsv($file, ['Net Profit', 'Rp ' . number_format($financeSummary['net_profit'], 0, ',', '.')]);
                fputcsv($file, ['Profit Margin', number_format($financeSummary['profit_margin'], 2) . '%']);
                fputcsv($file, []);

                // CASHIER SECTION
                fputcsv($file, ['2. CASHIER & SALES SUMMARY']);
                fputcsv($file, ['Total Sales', 'Rp ' . number_format($cashierSummary['total_sales'], 0, ',', '.')]);
                fputcsv($file, ['Total Transactions', $cashierSummary['total_transactions']]);
                fputcsv($file, ['Cash Sales', 'Rp ' . number_format($cashierSummary['cash_sales'], 0, ',', '.')]);
                fputcsv($file, ['Non-Cash Sales', 'Rp ' . number_format($cashierSummary['non_cash_sales'], 0, ',', '.')]);
                fputcsv($file, []);

                fputcsv($file, ['TOP SELLING PRODUCTS']);
                fputcsv($file, ['Product Name', 'Sold Count', 'Revenue']);
                foreach ($topProducts as $p) {
                    fputcsv($file, [$p->name, $p->total_sold, 'Rp ' . number_format($p->total_revenue, 0, ',', '.')]);
                }
                fputcsv($file, []);

                // WAREHOUSE SECTION
                fputcsv($file, ['3. WAREHOUSE & INVENTORY SUMMARY']);
                fputcsv($file, ['Total Valuation', 'Rp ' . number_format($warehouseSummary['total_valuation'], 0, ',', '.')]);
                fputcsv($file, ['Total Items in Stock', $warehouseSummary['total_items']]);
                fputcsv($file, ['Low Stock Alerts', $warehouseSummary['low_stock_count']]);
                fputcsv($file, []);

                if ($lowStockItems->count() > 0) {
                    fputcsv($file, ['LOW STOCK ITEMS']);
                    fputcsv($file, ['Product Name', 'Min Stock', 'Current Stock']);
                    foreach ($lowStockItems as $item) {
                        fputcsv($file, [$item->name, $item->min_stock, $item->current_stock]);
                    }
                    fputcsv($file, []);
                }

                // AUDIT SECTION
                fputcsv($file, ['4. RECENT AUDIT LOGS']);
                fputcsv($file, ['Date', 'User', 'Action', 'Notes']);
                foreach ($auditLogs as $log) {
                    fputcsv($file, [$log->created_at->format('Y-m-d H:i'), $log->user->name ?? 'System', $log->action, $log->notes]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.reports.print-all', compact(
            'startDate',
            'endDate',
            'warehouseSummary',
            'topMovers',
            'lowStockItems',
            'stockMovements',
            'cashierSummary',
            'topProducts',
            'cashierPerformance',
            'recentTransactions',
            'auditLogs',
            'financeSummary'
        ));
    }
}
