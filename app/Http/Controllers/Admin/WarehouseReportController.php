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

        $sections = $request->input('sections', ['summary', 'top_movers', 'low_stock', 'movements']);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.warehouse.print', [
                'summary' => $summary,
                'movements' => $movements,
                'lowStockItems' => $lowStockItems,
                'topMovers' => $topMovers,
                'auditLogs' => $auditLogs,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'sections' => $sections,
                'isPdf' => true
            ]);
            return $pdf->download('warehouse-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        if ($request->input('format') === 'csv') {
            $filename = 'warehouse-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($summary, $movements, $lowStockItems, $topMovers, $auditLogs, $startDate, $endDate) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $delimiter = ';';

                fputcsv($file, [__('admin.warehouse_reports_title'), $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')], $delimiter);
                fputcsv($file, [], $delimiter);

                // Summary
                fputcsv($file, [strtoupper(__('admin.quick_info'))], $delimiter);
                fputcsv($file, [__('admin.total_valuation'), 'Rp ' . number_format($summary['total_valuation'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.total_items_in_stock') ?? 'Total Items in Stock', number_format($summary['total_items'])], $delimiter);
                fputcsv($file, [__('admin.low_stock_alerts') ?? 'Low Stock Alerts', $summary['low_stock_count']], $delimiter);
                fputcsv($file, [__('admin.movements_in') ?? 'Movements IN', $summary['movements_in']], $delimiter);
                fputcsv($file, [__('admin.movements_out') ?? 'Movements OUT', $summary['movements_out']], $delimiter);
                fputcsv($file, [__('admin.adjustments') ?? 'Adjustments', $summary['movements_adjustment']], $delimiter);
                fputcsv($file, [], $delimiter);

                // Top Moving Items
                if ($topMovers->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.top_moving_items') ?? 'TOP MOVING ITEMS')], $delimiter);
                    fputcsv($file, [__('admin.product_name'), __('admin.barcode'), __('admin.movements_count') ?? 'Movements Count', __('admin.total_quantity') ?? 'Total Quantity'], $delimiter);
                    foreach ($topMovers as $mover) {
                        fputcsv($file, [
                            $mover->product->name,
                            $mover->product->barcode,
                            $mover->total_movements,
                            $mover->total_quantity
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                // Recent Stock Movements
                if ($movements->count() > 0) {
                    fputcsv($file, [strtoupper(__('menu.stock_movements') ?? 'RECENT STOCK MOVEMENTS')], $delimiter);
                    fputcsv($file, [__('admin.date'), __('admin.product'), __('admin.type'), __('admin.quantity'), __('admin.reference'), __('admin.user')], $delimiter);
                    foreach ($movements as $m) {
                        fputcsv($file, [
                            $m->created_at->format('Y-m-d H:i'),
                            $m->product->name,
                            strtoupper($m->type),
                            $m->quantity_change,
                            $m->reference ?? '-',
                            $m->user->name ?? 'System'
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                // Low Stock Items
                if ($lowStockItems->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.low_stock_items'))], $delimiter);
                    fputcsv($file, [__('admin.product_name'), __('admin.min_stock'), __('admin.current_stock')], $delimiter);
                    foreach ($lowStockItems as $item) {
                        fputcsv($file, [
                            $item->name,
                            $item->min_stock,
                            $item->current_stock
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Default to print view if no format scale
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
