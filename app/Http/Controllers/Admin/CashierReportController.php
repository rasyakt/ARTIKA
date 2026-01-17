<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CashierReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CashierReportController extends Controller
{
    protected $cashierReportService;

    public function __construct(CashierReportService $cashierReportService)
    {
        $this->cashierReportService = $cashierReportService;
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

        $summary = $this->cashierReportService->getSummaryStats($startDate, $endDate);
        $paymentBreakdown = $this->cashierReportService->getPaymentMethodBreakdown($startDate, $endDate);
        $topProducts = $this->cashierReportService->getTopSellingProducts($startDate, $endDate);
        $cashierPerformance = $this->cashierReportService->getTransactionsByUser($startDate, $endDate);
        $recentTransactions = $this->cashierReportService->getRecentTransactions($startDate, $endDate);
        $auditLogs = $this->cashierReportService->getCashierAuditLogs($startDate, $endDate);

        return view('admin.reports.cashier.index', compact(
            'summary',
            'paymentBreakdown',
            'topProducts',
            'cashierPerformance',
            'recentTransactions',
            'auditLogs',
            'startDate',
            'endDate',
            'period'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        $summary = $this->cashierReportService->getSummaryStats($startDate, $endDate);
        $paymentBreakdown = $this->cashierReportService->getPaymentMethodBreakdown($startDate, $endDate);
        $topProducts = $this->cashierReportService->getTopSellingProducts($startDate, $endDate);
        $cashierPerformance = $this->cashierReportService->getTransactionsByUser($startDate, $endDate);
        $recentTransactions = $this->cashierReportService->getRecentTransactions($startDate, $endDate, 50);
        $auditLogs = $this->cashierReportService->getCashierAuditLogs($startDate, $endDate);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.cashier.print', [
                'summary' => $summary,
                'paymentBreakdown' => $paymentBreakdown,
                'topProducts' => $topProducts,
                'cashierPerformance' => $cashierPerformance,
                'recentTransactions' => $recentTransactions,
                'auditLogs' => $auditLogs,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'isPdf' => true
            ]);
            return $pdf->download('cashier-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        return view('admin.reports.cashier.print', compact(
            'summary',
            'paymentBreakdown',
            'topProducts',
            'cashierPerformance',
            'recentTransactions',
            'auditLogs',
            'startDate',
            'endDate'
        ));
    }
}
