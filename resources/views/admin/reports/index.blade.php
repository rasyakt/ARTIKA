@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #6f5849;">📈 Sales Reports</h2>
            <p class="text-muted mb-0">View and analyze sales data</p>
        </div>

        <!-- Date Filter -->
        <div class="card shadow-sm mb-4" style="border-radius: 16px; border: none;">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">From Date</label>
                        <input type="date" class="form-control" name="from" value="{{ request('from', date('Y-m-01')) }}"
                            style="border-radius: 12px;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">To Date</label>
                        <input type="date" class="form-control" name="to" value="{{ request('to', date('Y-m-d')) }}"
                            style="border-radius: 12px;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Branch</label>
                        <select class="form-select" name="branch" style="border-radius: 12px;">
                            <option value="">All Branches</option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                            🔍 Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm"
                    style="border-radius: 16px; border: none; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
                    <div class="card-body text-white">
                        <h6 class="opacity-75 mb-2">Total Sales</h6>
                        <h3 class="fw-bold mb-0">Rp
                            {{ number_format(\App\Models\Transaction::where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm"
                    style="border-radius: 16px; border: none; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <div class="card-body text-white">
                        <h6 class="opacity-75 mb-2">Transactions</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Transaction::where('status', 'completed')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm"
                    style="border-radius: 16px; border: none; background: linear-gradient(135deg, #c17a5c 0%, #a18072 100%);">
                    <div class="card-body text-white">
                        <h6 class="opacity-75 mb-2">Avg Transaction</h6>
                        <h3 class="fw-bold mb-0">Rp
                            {{ number_format(\App\Models\Transaction::where('status', 'completed')->avg('total_amount') ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm"
                    style="border-radius: 16px; border: none; background: linear-gradient(135deg, #0284c7 0%, #075985 100%);">
                    <div class="card-body text-white">
                        <h6 class="opacity-75 mb-2">Products Sold</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\TransactionItem::sum('quantity') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">📋 Transaction History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">Invoice</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Date</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Cashier</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Items</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Total</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Transaction::with(['user', 'items'])->where('status', 'completed')->latest()->limit(50)->get() as $transaction)
                                <tr>
                                    <td class="ps-4 fw-bold" style="color: #85695a;">{{ $transaction->invoice_no }}</td>
                                    <td class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->items->count() }} items</td>
                                    <td class="fw-bold" style="color: #c17a5c;">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge"
                                            style="background: #e0cec7; color: #6f5849;">{{ ucfirst($transaction->payment_method) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection