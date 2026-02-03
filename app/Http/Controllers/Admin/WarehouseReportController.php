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

        if ($request->input('format') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\GenericReportExport('admin.reports.warehouse.export_excel', compact(
                    'summary',
                    'movements',
                    'lowStockItems',
                    'topMovers',
                    'startDate',
                    'endDate'
                )),
                'warehouse-report-' . $startDate->format('Y-m-d') . '.xlsx'
            );
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
                fputcsv($file, ['WARHOUSE REPORT', $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
                fputcsv($file, []);

                // Summary
                fputcsv($file, ['SUMMARY STATISTICS']);
                fputcsv($file, ['Total Valuation', 'Rp ' . number_format($summary['total_valuation'], 0, ',', '.')]);
                fputcsv($file, ['Total Items in Stock', number_format($summary['total_items'])]);
                fputcsv($file, ['Low Stock Alerts', $summary['low_stock_count']]);
                fputcsv($file, ['Movements IN', $summary['movements_in']]);
                fputcsv($file, ['Movements OUT', $summary['movements_out']]);
                fputcsv($file, ['Adjustments', $summary['movements_adjustment']]);
                fputcsv($file, []);

                // Top Moving Items
                if ($topMovers->count() > 0) {
                    fputcsv($file, ['TOP MOVING ITEMS']);
                    fputcsv($file, ['Product Name', 'Barcode', 'Movements Count', 'Total Quantity']);
                    foreach ($topMovers as $mover) {
                        fputcsv($file, [
                            $mover->product->name,
                            $mover->product->barcode,
                            $mover->total_movements,
                            $mover->total_quantity
                        ]);
                    }
                    fputcsv($file, []);
                }

                // Recent Stock Movements
                if ($movements->count() > 0) {
                    fputcsv($file, ['RECENT STOCK MOVEMENTS']);
                    fputcsv($file, ['Date', 'Product', 'Type', 'Quantity', 'Reference', 'User']);
                    foreach ($movements as $m) {
                        fputcsv($file, [
                            $m->created_at->format('Y-m-d H:i'),
                            $m->product->name,
                            strtoupper($m->type),
                            $m->quantity_change,
                            $m->reference ?? '-',
                            $m->user->name ?? 'System'
                        ]);
                    }
                    fputcsv($file, []);
                }

                // Low Stock Items
                if ($lowStockItems->count() > 0) {
                    fputcsv($file, ['LOW STOCK ITEMS']);
                    fputcsv($file, ['Product Name', 'Min Stock', 'Current Stock']);
                    foreach ($lowStockItems as $item) {
                        fputcsv($file, [
                            $item->name,
                            $item->min_stock,
                            $item->current_stock
                        ]);
                    }
                    fputcsv($file, []);
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
