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
                fputcsv($file, ['FINANCE REPORT', $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
                fputcsv($file, []);

                // Summary
                fputcsv($file, ['FINANCIAL SUMMARY']);
                fputcsv($file, ['Gross Revenue', 'Rp ' . number_format($summary['gross_revenue'], 0, ',', '.')]);
                fputcsv($file, ['COGS (Cost of Goods Sold)', 'Rp ' . number_format($summary['cogs'], 0, ',', '.')]);
                fputcsv($file, ['Gross Profit', 'Rp ' . number_format($summary['gross_profit'], 0, ',', '.')]);
                fputcsv($file, ['Operational Expenses', 'Rp ' . number_format($summary['total_expenses'], 0, ',', '.')]);
                fputcsv($file, ['Stock Procurement', 'Rp ' . number_format($summary['total_procurement'], 0, ',', '.')]);
                fputcsv($file, ['Net Profit', 'Rp ' . number_format($summary['net_profit'], 0, ',', '.')]);
                fputcsv($file, ['Profit Margin', number_format($summary['profit_margin'], 2) . '%']);
                fputcsv($file, []);

                // Daily Data
                fputcsv($file, ['DAILY DATA']);
                fputcsv($file, ['Date', 'Revenue', 'COGS', 'Expenses', 'Procurement', 'Profit', 'Margin %']);
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
                    ]);
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
