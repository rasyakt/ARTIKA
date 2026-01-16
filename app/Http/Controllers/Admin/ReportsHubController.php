<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WarehouseReportService;
use App\Services\CashierReportService;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsHubController extends Controller
{
    protected $warehouseService;
    protected $cashierService;

    public function __construct(WarehouseReportService $warehouseService, CashierReportService $cashierService)
    {
        $this->warehouseService = $warehouseService;
        $this->cashierService = $cashierService;
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
        $lowStockItems = $this->warehouseService->getLowStockItems($startDate, $endDate);
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
            'auditLogs'
        ));
    }
}
