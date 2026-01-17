<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
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
     * Download audit report as PDF
     */
    public function downloadPdf(Request $request)
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

        $pdf = Pdf::loadView('admin.audit.pdf', compact('logs', 'summary', 'startDate', 'endDate'));

        $filename = 'audit-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';

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

        $logs = $query->with('user.role')
            ->latest()
            ->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Write CSV headers with new columns
        fputcsv($handle, [
            'Tanggal',
            'User',
            'Role',
            'NIS',
            'Username',
            'Aksi',
            'Model',
            'Amount',
            'Metode Pembayaran',
            'IP Address',
            'MAC Address',
            'Device',
            'User Agent',
            'Catatan'
        ]);

        // Write data rows
        foreach ($logs as $log) {
            $user = $log->user;
            $isCashier = $user && $user->role && $user->role->name === 'cashier';

            fputcsv($handle, [
                $log->created_at->format('Y-m-d H:i:s'),
                $user?->name ?? 'System',
                $user?->role->name ?? '',
                $isCashier ? ($user->nis ?? '-') : '',
                $isCashier ? ($user->username ?? '-') : '',
                $log->action,
                $log->model_type . ($log->model_id ? '#' . $log->model_id : ''),
                $log->amount ? 'Rp' . number_format((float) $log->amount, 0, ',', '.') : '',
                $log->payment_method ?? '',
                $log->ip_address,
                $log->mac_address ?? 'N/A',
                $log->device_name ?? 'Unknown',
                $log->user_agent,
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
