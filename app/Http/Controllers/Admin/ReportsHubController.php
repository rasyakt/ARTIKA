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

                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $delimiter = ';';

                fputcsv($file, [__('admin.print_all_reports'), $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')], $delimiter);
                fputcsv($file, [], $delimiter);

                // FINANCE SECTION
                fputcsv($file, ['1. ' . __('admin.finance_report')], $delimiter);
                fputcsv($file, [__('admin.gross_revenue'), 'Rp ' . number_format($financeSummary['gross_revenue'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.cogs'), 'Rp ' . number_format($financeSummary['cogs'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.gross_profit'), 'Rp ' . number_format($financeSummary['gross_profit'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('menu.operational_expenses'), 'Rp ' . number_format($financeSummary['total_expenses'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.net_profit'), 'Rp ' . number_format($financeSummary['net_profit'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.profit_margin'), number_format($financeSummary['profit_margin'], 2) . '%'], $delimiter);
                fputcsv($file, [], $delimiter);

                // CASHIER SECTION
                fputcsv($file, ['2. ' . __('admin.cashier_report')], $delimiter);
                fputcsv($file, [__('admin.total_sales'), 'Rp ' . number_format($cashierSummary['total_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.total_transactions'), $cashierSummary['total_transactions']], $delimiter);
                fputcsv($file, [__('admin.cash_sales') ?? 'Cash Sales', 'Rp ' . number_format($cashierSummary['cash_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.non_cash_sales') ?? 'Non-Cash Sales', 'Rp ' . number_format($cashierSummary['non_cash_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [], $delimiter);

                fputcsv($file, [strtoupper(__('admin.top_selling_products') ?? 'TOP SELLING PRODUCTS')], $delimiter);
                fputcsv($file, [__('admin.product_name'), __('admin.sold_count') ?? 'Sold Count', __('admin.revenue')], $delimiter);
                foreach ($topProducts as $p) {
                    fputcsv($file, [$p->name, $p->total_sold, 'Rp ' . number_format($p->total_revenue, 0, ',', '.')], $delimiter);
                }
                fputcsv($file, [], $delimiter);

                // WAREHOUSE SECTION
                fputcsv($file, ['3. ' . __('admin.warehouse_report')], $delimiter);
                fputcsv($file, [__('admin.total_valuation'), 'Rp ' . number_format($warehouseSummary['total_valuation'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.total_items_in_stock') ?? 'Total Items in Stock', $warehouseSummary['total_items']], $delimiter);
                fputcsv($file, [__('admin.low_stock_alerts') ?? 'Low Stock Alerts', $warehouseSummary['low_stock_count']], $delimiter);
                fputcsv($file, [], $delimiter);

                if ($lowStockItems->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.low_stock_items'))], $delimiter);
                    fputcsv($file, [__('admin.product_name'), __('admin.current_stock'), __('admin.min_stock')], $delimiter);
                    foreach ($lowStockItems as $item) {
                        fputcsv($file, [$item->name, $item->current_stock, $item->min_stock], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                // AUDIT SECTION
                fputcsv($file, ['4. ' . __('admin.logs_report')], $delimiter);
                fputcsv($file, [__('admin.date'), __('admin.user'), __('admin.action'), __('admin.notes')], $delimiter);
                foreach ($auditLogs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i'),
                        $log->user->name ?? 'System',
                        $log->action,
                        $log->notes
                    ], $delimiter);
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
