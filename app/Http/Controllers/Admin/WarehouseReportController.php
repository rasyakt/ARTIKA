<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WarehouseReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class WarehouseReportController extends Controller
{
    protected $warehouseReportService;

    public function __construct(WarehouseReportService $warehouseReportService)
    {
        $this->warehouseReportService = $warehouseReportService;
    }

    public function index(Request $request)
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

        $filters = $request->only(['search', 'category_id', 'stock_status']);

        $summary = $this->warehouseReportService->getSummaryStats($startDate, $endDate, $filters);
        $movements = $this->warehouseReportService->getStockMovements($startDate, $endDate, null, 10, 'movements_page', $filters);
        $lowStockItems = $this->warehouseReportService->getLowStockItems(10, 'low_stock_page', $filters);
        $topMovers = $this->warehouseReportService->getTopMovingItems($startDate, $endDate, 5, $filters);
        $auditLogs = $this->warehouseReportService->getWarehouseAuditLogs($startDate, $endDate, 10, 'audit_page');
        $categories = \App\Models\Category::orderBy('name')->get();
        $categoryId = $request->input('category_id');
        $stockStatus = $request->input('stock_status');
        $search = $request->input('search');

        return view('admin.reports.warehouse.index', compact(
            'summary',
            'movements',
            'lowStockItems',
            'topMovers',
            'auditLogs',
            'startDate',
            'endDate',
            'period',
            'categories',
            'categoryId',
            'stockStatus',
            'search'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        $summary = $this->warehouseReportService->getSummaryStats($startDate, $endDate);
        $movements = $this->warehouseReportService->getStockMovements($startDate, $endDate);
        $lowStockItems = $this->warehouseReportService->getLowStockItems();
        $topMovers = $this->warehouseReportService->getTopMovingItems($startDate, $endDate);
        $auditLogs = $this->warehouseReportService->getWarehouseAuditLogs($startDate, $endDate);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.warehouse.print', [
                'summary' => $summary,
                'movements' => $movements,
                'lowStockItems' => $lowStockItems,
                'topMovers' => $topMovers,
                'auditLogs' => $auditLogs,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'isPdf' => true
            ]);
            return $pdf->download('warehouse-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        // For now, we return a print-optimized view
        return view('admin.reports.warehouse.print', compact(
            'summary',
            'movements',
            'lowStockItems',
            'topMovers',
            'auditLogs',
            'startDate',
            'endDate'
        ));
    }
}
