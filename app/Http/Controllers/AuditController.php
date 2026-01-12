<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Branch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditController extends Controller
{
    /**
     * Display audit logs listing
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filter by branch
        if (auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->with('user', 'branch')
            ->latest()
            ->paginate(50);

        $actions = AuditLog::distinct()->pluck('action');
        $users = AuditLog::where('branch_id', auth()->user()->branch_id)
            ->distinct()
            ->pluck('user_id')
            ->map(function ($userId) {
                return \App\Models\User::find($userId);
            })
            ->filter()
            ->unique('id');

        return view('admin.audit.index', compact('logs', 'actions', 'users'));
    }

    /**
     * Download audit report as PDF
     */
    public function downloadPdf(Request $request)
    {
        $query = AuditLog::query();

        // Filter by branch
        if (auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        // Filter by date range
        $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $query->whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->with('user', 'branch')
            ->latest()
            ->get();

        $branch = auth()->user()->branch;
        $summary = $this->generateSummary($logs);

        $pdf = Pdf::loadView('admin.audit.pdf', compact('logs', 'branch', 'summary', 'startDate', 'endDate'));
        
        $filename = 'audit-report-' . $branch->name . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate audit summary statistics
     */
    private function generateSummary($logs)
    {
        $summary = [
            'total_logs' => $logs->count(),
            'total_transactions' => $logs->where('action', 'transaction_created')->count(),
            'total_amount' => $logs->where('action', 'transaction_created')->sum('amount'),
            'by_payment_method' => $logs->where('action', 'transaction_created')
                ->groupBy('payment_method')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'total' => $group->sum('amount'),
                    ];
                }),
            'by_user' => $logs->groupBy('user_id')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'user' => $group->first()->user,
                    ];
                }),
            'by_action' => $logs->groupBy('action')
                ->map(function ($group) {
                    return $group->count();
                }),
        ];

        return $summary;
    }

    /**
     * Export audit logs to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = AuditLog::query();

        if (auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->with('user', 'branch')
            ->latest()
            ->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Write CSV headers
        fputcsv($handle, [
            'Tanggal',
            'User',
            'Branch',
            'Aksi',
            'Model',
            'Amount',
            'Metode Pembayaran',
            'IP Address',
            'Catatan'
        ]);

        // Write data rows
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user?->name ?? 'System',
                $log->branch?->name ?? 'N/A',
                $log->action,
                $log->model_type . ($log->model_id ? '#' . $log->model_id : ''),
                $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '',
                $log->payment_method ?? '',
                $log->ip_address,
                $log->notes,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Auto audit - log transaction
     */
    public static function logTransaction($transaction, $action = 'transaction_created')
    {
        return AuditLog::log(
            $action,
            'Transaction',
            $transaction->id,
            $transaction->total_amount,
            $transaction->payment_method,
            [
                'subtotal' => $transaction->subtotal,
                'discount' => $transaction->discount,
                'items_count' => $transaction->items()->count(),
            ],
            'Invoice: ' . $transaction->invoice_no
        );
    }
}
