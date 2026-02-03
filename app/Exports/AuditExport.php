<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = AuditLog::query()->with('user.role');

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [
                $this->filters['start_date'] . ' 00:00:00',
                $this->filters['end_date'] . ' 23:59:59'
            ]);
        }

        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }

        if (!empty($this->filters['role_id'])) {
            $query->whereHas('user', function ($q) {
                $q->where('role_id', $this->filters['role_id']);
            });
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'User',
            'Role',
            'Aksi',
            'Tipe Model',
            'ID Model',
            'Nominal',
            'Metode Pembayaran',
            'Keterangan/Notes'
        ];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('Y-m-d H:i:s'),
            $log->user->name ?? 'System',
            $log->user->role->name ?? '-',
            $log->action,
            $log->model_type,
            $log->model_id,
            $log->amount,
            $log->payment_method,
            $log->notes,
        ];
    }
}
