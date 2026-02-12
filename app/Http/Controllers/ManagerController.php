<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayTransactions = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed');

        $stats = [
            'today_sales' => (clone $todayTransactions)->sum('total_amount'),
            'today_count' => (clone $todayTransactions)->count(),
            'today_avg' => (clone $todayTransactions)->avg('total_amount') ?? 0,
        ];

        $recentTransactions = Transaction::with(['user', 'items.product'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('manager.dashboard', compact('stats', 'recentTransactions'));
    }
}
