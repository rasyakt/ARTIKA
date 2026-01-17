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
