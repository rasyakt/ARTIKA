<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;

class AuditController extends Controller
{
    /**
     * Display audit logs listing
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

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

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_id', $request->role_id);
            });
        }

        // Search by NIS or Username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $logs = $query->with('user')
            ->latest()
            ->paginate(10)
            ->appends($request->all());

        $actions = AuditLog::distinct()->pluck('action');
        $roles = Role::all();

        return view('admin.audit.index', compact('logs', 'actions', 'roles'));
    }

    /**
     * Export audit report (PDF or Print)
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

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

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_id', $request->role_id);
            });
        }

        // Search by NIS or Username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $logs = $query->with('user.role')
            ->latest()
            ->get();

        $summary = $this->generateSummary($logs);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.audit.print', [
                'logs' => $logs,
                'summary' => $summary,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'isPdf' => true
            ]);

            $filename = 'audit-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            return $pdf->download($filename);
        }

        if ($request->input('format') === 'csv') {
            $filename = 'audit-log-' . now()->format('Y-m-d-H-i-s') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($logs, $startDate, $endDate) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                $delimiter = ';';

                fputcsv($file, [__('admin.logs_report'), $startDate . ' - ' . $endDate], $delimiter);
                fputcsv($file, [], $delimiter);

                fputcsv($file, [
                    __('admin.date'),
                    __('admin.user'),
                    __('admin.role') ?? 'Role',
                    __('admin.action'),
                    __('admin.model') ?? 'Model',
                    'ID',
                    __('admin.amount'),
                    'IP Address',
                    __('admin.device') ?? 'Device',
                    'URL',
                    __('admin.notes')
                ], $delimiter);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user->name ?? 'System',
                        $log->user->role->name ?? '-',
                        $log->action,
                        $log->model_type,
                        $log->model_id,
                        in_array($log->action, ['transaction_created', 'payment_received', 'refund', 'expense_created'])
                        ? ($log->amount ? 'Rp ' . number_format($log->amount, 0, ',', '.') : '-')
                        : ($log->amount ? number_format($log->amount, 0, ',', '.') : '-'),
                        $log->ip_address,
                        $log->device_name,
                        $log->url,
                        $log->notes
                    ], $delimiter);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.audit.print', compact('logs', 'summary', 'startDate', 'endDate'));
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

    /**
     * Clear audit logs (filtered or all)
     */
    public function clear(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'clear_type' => 'required|string|in:filtered,backup_clear'
        ]);

        // Verify password
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()
                ->with('error', __('admin.password_incorrect') ?? 'Password yang anda masukkan salah')
                ->withInput();
        }

        $query = AuditLog::query();

        // Apply filters if needed
        if ($request->clear_type === 'filtered') {
            // Safety check: at least one filter must be set to prevent accidental deletion of all logs
            $hasFilters = $request->filled('start_date') || $request->filled('end_date') ||
                $request->filled('action') || $request->filled('role_id') ||
                $request->filled('search');

            if (!$hasFilters) {
                return redirect()->back()
                    ->with('error', __('admin.filter_required_for_clear') ?? 'Minimal satu filter harus diisi untuk menghapus log secara selektif. Gunakan "Backup & Clear" untuk menghapus semua.')
                    ->withInput();
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
            if ($request->filled('role_id')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('role_id', $request->role_id);
                });
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            }
        }

        // Handle Backup & Clear
        if ($request->clear_type === 'backup_clear') {
            $logs = $query->with('user.role')->latest()->get();
            $startDate = $logs->last()?->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
            $endDate = $logs->first()?->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');

            // Deletion first
            $query->delete();

            // Log the action AFTER deletion so it remains in the new empty log
            AuditLog::log(
                'audit_backup_clear',
                null,
                null,
                null,
                null,
                null,
                'Full backup and clear performed by ' . auth()->user()->name
            );

            // Return CSV response
            return $this->downloadCsv($logs, $startDate, $endDate);
        }

        $query->delete();

        // Log filtered deletion if applicable
        AuditLog::log(
            'audit_clear',
            null,
            null,
            null,
            null,
            null,
            'Audit logs cleared (Type: ' . $request->clear_type . ') by ' . auth()->user()->name
        );

        return redirect()->route('admin.audit.index')
            ->with('success', __('admin.logs_cleared'));
    }

    /**
     * Helper to download CSV
     */
    private function downloadCsv($logs, $startDate, $endDate)
    {
        $filename = 'audit-backup-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($logs, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $delimiter = ';';

            fputcsv($file, [__('admin.logs_report'), $startDate . ' - ' . $endDate], $delimiter);
            fputcsv($file, [], $delimiter);
            fputcsv($file, [
                __('admin.date'),
                __('admin.user'),
                __('admin.role') ?? 'Role',
                __('admin.action'),
                __('admin.model') ?? 'Model',
                'ID',
                __('admin.amount'),
                'IP Address',
                __('admin.device') ?? 'Device',
                'URL',
                __('admin.notes')
            ], $delimiter);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'System',
                    $log->user->role->name ?? '-',
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    in_array($log->action, ['transaction_created', 'payment_received', 'refund', 'expense_created'])
                    ? ($log->amount ? 'Rp ' . number_format($log->amount, 0, ',', '.') : '-')
                    : ($log->amount ? number_format($log->amount, 0, ',', '.') : '-'),
                    $log->ip_address,
                    $log->device_name,
                    $log->url,
                    $log->notes
                ], $delimiter);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
