<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnTransaction;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of returns
     */
    public function index(Request $request)
    {
        $query = ReturnTransaction::with(['transaction', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $returns = $query->paginate(10);

        return view('admin.returns.index', compact('returns'));
    }

    /**
     * Store a newly created return in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            if (\App\Models\Setting::get('cashier_enable_returns', true) === 'false') {
                throw new \Exception('Return functionality is currently disabled by administrator.');
            }
            // Filter out items with quantity 0
            $items = array_filter($request->items, function ($item) {
                return isset($item['quantity']) && $item['quantity'] > 0;
            });

            if (empty($items)) {
                return back()->with('error', 'Please select at least one item to return with a quantity greater than 0.');
            }

            $this->transactionService->processReturn(
                $request->transaction_id,
                $items,
                $request->reason
            );

            return back()->with('success', 'Return processed successfully and stock updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified return
     */
    public function show($id)
    {
        $return = ReturnTransaction::with(['transaction.user', 'user'])->findOrFail($id);
        return response()->json($return);
    }
}
