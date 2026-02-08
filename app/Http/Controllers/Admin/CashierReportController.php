<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CashierReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CashierReportController extends Controller
{
    protected $cashierReportService;
    protected $transactionService;

    public function __construct(CashierReportService $cashierReportService, \App\Services\TransactionService $transactionService)
    {
        $this->cashierReportService = $cashierReportService;
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $action = $request->input('action');

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
        $recentTransactions = $this->cashierReportService->getRecentTransactions($startDate, $endDate, 20, $search, 10, 'transactions_page');
        $auditLogs = $this->cashierReportService->getCashierAuditLogs($startDate, $endDate, $search, $action, 10, 'audit_page');
        $categorySales = $this->cashierReportService->getSalesByCategory($startDate, $endDate);
        $discountSummary = $this->cashierReportService->getDiscountSummary($startDate, $endDate);

        $actions = \App\Models\AuditLog::distinct()->pluck('action');

        return view('admin.reports.cashier.index', compact(
            'summary',
            'paymentBreakdown',
            'topProducts',
            'cashierPerformance',
            'recentTransactions',
            'auditLogs',
            'categorySales',
            'discountSummary',
            'startDate',
            'endDate',
            'period',
            'actions',
            'search',
            'action'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();
        $search = $request->input('search');
        $action = $request->input('action');

        $summary = $this->cashierReportService->getSummaryStats($startDate, $endDate);
        $paymentBreakdown = $this->cashierReportService->getPaymentMethodBreakdown($startDate, $endDate);
        $topProducts = $this->cashierReportService->getTopSellingProducts($startDate, $endDate);
        $cashierPerformance = $this->cashierReportService->getTransactionsByUser($startDate, $endDate);
        $recentTransactions = $this->cashierReportService->getRecentTransactions($startDate, $endDate, 50, $search);
        $auditLogs = $this->cashierReportService->getCashierAuditLogs($startDate, $endDate, $search, $action);

        $sections = $request->input('sections', ['summary', 'payment_methods', 'top_products', 'cashier_performance', 'recent_transactions']);

        $categorySales = $this->cashierReportService->getSalesByCategory($startDate, $endDate);
        $discountSummary = $this->cashierReportService->getDiscountSummary($startDate, $endDate);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.cashier.print', [
                'summary' => $summary,
                'paymentBreakdown' => $paymentBreakdown,
                'topProducts' => $topProducts,
                'cashierPerformance' => $cashierPerformance,
                'recentTransactions' => $recentTransactions,
                'auditLogs' => $auditLogs,
                'categorySales' => $categorySales,
                'discountSummary' => $discountSummary,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'sections' => $sections,
                'isPdf' => true,
                'search' => $search,
                'action' => $action
            ]);
            return $pdf->download('cashier-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
        }

        if ($request->input('format') === 'csv') {
            $filename = 'cashier-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($summary, $topProducts, $cashierPerformance, $recentTransactions, $startDate, $endDate) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $delimiter = ';';

                fputcsv($file, [__('admin.cashier_report'), $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')], $delimiter);
                fputcsv($file, [], $delimiter);

                // Summary
                fputcsv($file, [strtoupper(__('admin.quick_info'))], $delimiter);
                fputcsv($file, [__('admin.total_sales'), 'Rp ' . number_format($summary['total_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.total_transactions'), $summary['total_transactions']], $delimiter);
                fputcsv($file, [__('admin.average_transaction'), 'Rp ' . number_format($summary['average_transaction'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.cash_sales') ?? 'Cash Sales', 'Rp ' . number_format($summary['cash_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [__('admin.non_cash_sales') ?? 'Non-Cash Sales', 'Rp ' . number_format($summary['non_cash_sales'], 0, ',', '.')], $delimiter);
                fputcsv($file, [], $delimiter);

                // Top Selling Products
                if ($topProducts->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.top_selling_products') ?? 'TOP SELLING PRODUCTS')], $delimiter);
                    fputcsv($file, [__('admin.product_name'), __('admin.barcode'), __('admin.sold_count') ?? 'Sold Count', __('admin.revenue')], $delimiter);
                    foreach ($topProducts as $product) {
                        fputcsv($file, [
                            $product->name,
                            $product->barcode,
                            $product->total_sold,
                            'Rp ' . number_format($product->total_revenue, 0, ',', '.')
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                // Cashier Performance
                if ($cashierPerformance->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.cashier_performance') ?? 'CASHIER PERFORMANCE')], $delimiter);
                    fputcsv($file, [__('admin.name'), __('admin.role'), __('admin.transaction_count') ?? 'Transaction Count', __('admin.total_sales')], $delimiter);
                    foreach ($cashierPerformance as $p) {
                        fputcsv($file, [
                            $p->user->name,
                            $p->user->role->name,
                            $p->transaction_count,
                            'Rp ' . number_format($p->total_sales, 0, ',', '.')
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                // Recent Transactions
                if ($recentTransactions->count() > 0) {
                    fputcsv($file, [strtoupper(__('admin.recent_transactions') ?? 'RECENT TRANSACTIONS')], $delimiter);
                    fputcsv($file, [__('admin.invoice'), __('admin.date'), __('admin.cashier'), __('admin.payment_method'), __('admin.amount'), __('admin.status')], $delimiter);
                    foreach ($recentTransactions as $tx) {
                        fputcsv($file, [
                            $tx->invoice_no,
                            $tx->created_at->format('Y-m-d H:i'),
                            $tx->user->name,
                            $tx->payment_method,
                            'Rp ' . number_format($tx->total_amount, 0, ',', '.'),
                            strtoupper($tx->status)
                        ], $delimiter);
                    }
                    fputcsv($file, [], $delimiter);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.reports.cashier.print', compact(
            'summary',
            'paymentBreakdown',
            'topProducts',
            'cashierPerformance',
            'recentTransactions',
            'auditLogs',
            'startDate',
            'endDate',
            'search',
            'action'
        ));
    }

    public function rollback($id)
    {
        try {
            $this->transactionService->rollbackTransaction($id);
            return back()->with('success', 'Transaction successfully rolled back and stock restored.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getTransactionItems($id)
    {
        $transaction = \App\Models\Transaction::with(['items.product', 'user', 'returns'])->findOrFail($id);
        return response()->json([
            'invoice_no' => $transaction->invoice_no,
            'cashier_name' => $transaction->user->name ?? 'System',
            'date' => $transaction->created_at->format('d M Y, H:i'),
            'subtotal' => $transaction->subtotal,
            'discount' => $transaction->discount,
            'total_amount' => $transaction->total_amount,
            'payment_method' => $transaction->payment_method,
            'cash_amount' => $transaction->cash_amount,
            'change_amount' => $transaction->change_amount,
            'status' => $transaction->status,
            'total_refunded' => $transaction->total_refunded,
            'items' => $transaction->items->map(function ($item) use ($transaction) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'returned_quantity' => $transaction->getReturnedQuantity($item->product_id),
                    'price' => $item->price,
                    'subtotal' => $item->subtotal
                ];
            })
        ]);
    }

    public function printReceipt($id)
    {
        $transaction = \App\Models\Transaction::with(['user', 'items.product'])
            ->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = \App\Models\Transaction::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|string',
            'cash_amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = $transaction->total_amount;
        $cashAmount = $request->cash_amount;
        $changeAmount = max(0, $cashAmount - $totalAmount);

        $transaction->update([
            'payment_method' => $request->payment_method,
            'cash_amount' => $cashAmount,
            'change_amount' => $changeAmount,
        ]);

        return back()->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->transactionService->deleteTransaction($id);
            return back()->with('success', 'Transaction and related logs deleted, stock restored if necessary.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }
}
