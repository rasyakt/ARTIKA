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

        return view('admin.reports.finance.print', compact(
            'summary',
            'dailyData',
            'startDate',
            'endDate',
            'period'
        ));
    }
}
