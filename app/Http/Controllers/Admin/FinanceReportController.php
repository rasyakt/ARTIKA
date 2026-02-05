<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinanceReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FinanceReportController extends Controller
{
    protected $financeService;

    public function __construct(FinanceReportService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display financial report dashboard
     */
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

        $summary = $this->financeService->getFinancialSummary($startDate, $endDate);
        $allDailyData = $this->financeService->getDailyFinanceData($startDate, $endDate);

        // Manually paginate the dailyData array for the table
        $itemCollection = collect($allDailyData);
        $perPage = 10;
        $pageName = 'daily_page';
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $dailyData = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
            'pageName' => $pageName,
        ]);

        return view('admin.reports.finance.index', compact(
            'summary',
            'dailyData',
            'allDailyData',
            'startDate',
            'endDate',
            'period'
        ));
    }

    /**
     * Print financial report
     */
    public function export(Request $request)
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

        $summary = $this->financeService->getFinancialSummary($startDate, $endDate);
        $dailyData = $this->financeService->getDailyFinanceData($startDate, $endDate);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.finance.print', [
                'summary' => $summary,
                'dailyData' => $dailyData,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'period' => $period,
                'isPdf' => true
            ]);
            return $pdf->download('finance-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        if ($request->input('format') === 'csv') {
            $filename = 'finance-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($summary, $dailyData, $startDate, $endDate) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $delimiter = ';';

                fputcsv($file, [__('admin.finance_report'), $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')], $delimiter);
                fputcsv($file, [], $delimiter);

                // Summary
                fputcsv($file, [strtoupper(__('admin.quick_info'))], $delimiter);
                fputcsv($file, [__('admin.gross_revenue'), 'Rp ' . number_format($summary['gross_revenue'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.cogs'), 'Rp ' . number_format($summary['cogs'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.gross_profit'), 'Rp ' . number_format($summary['gross_profit'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('menu.operational_expenses'), 'Rp ' . number_format($summary['total_expenses'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.stock_procurement') ?? 'Stock Procurement', 'Rp ' . number_format($summary['total_procurement'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.net_profit'), 'Rp ' . number_format($summary['net_profit'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.profit_margin'), number_format($summary['profit_margin'], 2) . '%'], $delimiter);
                fputcsv($file, [], $delimiter);

                // Daily Data
                fputcsv($file, [strtoupper(__('admin.daily_data') ?? 'DAILY DATA')], $delimiter);
                fputcsv($file, [__('admin.date'), __('admin.revenue'), __('admin.cogs'), __('menu.operational_expenses'), __('admin.procurement') ?? 'Procurement', __('admin.profit') ?? 'Profit', __('admin.margin') . ' %'], $delimiter);
                foreach ($dailyData as $day) {
                    $margin = $day['revenue'] > 0 ? ($day['profit'] / $day['revenue']) * 100 : 0;
                    fputcsv($file, [
                        $day['date'],
                        'Rp ' . number_format($day['revenue'], 0, ',', '.'),
                        'Rp ' . number_format($day['cogs'], 0, ',', '.'),
                        'Rp ' . number_format($day['expenses'], 0, ',', '.'),
                        'Rp ' . number_format($day['procurement'], 0, ',', '.'),
                        'Rp ' . number_format($day['profit'], 0, ',', '.'),
                        number_format($margin, 2) . '%'
                    ], $delimiter);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.reports.finance.print', compact(
            'summary',
            'dailyData',
            'startDate',
            'endDate',
            'period'
        ));
    }
}
