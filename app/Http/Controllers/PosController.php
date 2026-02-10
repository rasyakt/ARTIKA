<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeldTransaction;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    protected $transactionService;
    protected $productRepository;

    public function __construct(
        \App\Services\TransactionService $transactionService,
        \App\Interfaces\ProductRepositoryInterface $productRepository
    ) {
        $this->transactionService = $transactionService;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getAllProducts();
        $categories = Category::all();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $heldTransactions = HeldTransaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $activePromos = \App\Models\Promo::active()->get();

        return view('pos.index', compact('products', 'categories', 'paymentMethods', 'heldTransactions', 'activePromos'));
    }

    public function logs(Request $request)
    {
        $query = AuditLog::where('user_id', Auth::id());

        // Filter by type
        if ($request->has('type')) {
            $type = $request->type;
            if ($type === 'login') {
                $query->where('action', 'login');
            } elseif ($type === 'transaction') {
                $query->where('action', 'transaction_created');
            }
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pos.logs', compact('logs'));
    }

    public function history(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('items.product');

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Get Summary Stats (before pagination)
        $summaryQuery = clone $query;
        $totalRevenue = $summaryQuery->sum('total_amount') ?? 0;

        // Get Sold Items Summary
        $soldItems = TransactionItem::whereIn('transaction_id', $summaryQuery->select('id'))
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'), DB::raw('SUM(transaction_items.subtotal) as total_sales'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->get();

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $enableReturns = \App\Models\Setting::get('cashier_enable_returns', true);

        return view('pos.history', compact('transactions', 'totalRevenue', 'soldItems', 'enableReturns'));
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->with(['user', 'items.product'])
            ->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }

    public function scanner()
    {
        $products = $this->productRepository->getAllProducts();
        return view('pos.scanner', compact('products'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'cash_amount' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric|min:0',
            'payment_proof' => 'nullable|image|max:5120', // Max 5MB
        ]);

        try {
            $data = [
                'user_id' => Auth::id(),
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'cash_amount' => $validated['cash_amount'] ?? $validated['total_amount'],
                'change_amount' => $validated['change_amount'] ?? 0,
                'payment_proof' => null,
                'status' => 'completed',
            ];

            // Handle Payment Proof for Non-Cash
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/payment_proofs'), $filename);
                $data['payment_proof'] = 'uploads/payment_proofs/' . $filename;
            } elseif ($validated['payment_method'] === 'non-cash') {
                // If strictly required, throw error. For now, we allow it (or frontend validation handles it).
                // return response()->json(['success' => false, 'message' => 'Bukti pembayaran wajib diunggah untuk transaksi non-tunai.'], 422);
            }

            $items = $validated['items'];

            return DB::transaction(function () use ($data, $items) {
                $transaction = $this->transactionService->processTransaction($data, $items);

                // AUTO AUDIT LOG
                AuditLog::log(
                    'transaction_created',
                    'Transaction',
                    $transaction->id,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    [
                        'subtotal' => $transaction->subtotal,
                        'discount' => $transaction->discount,
                        'items_count' => count($items),
                        'cash_amount' => $data['cash_amount'],
                        'change_amount' => $data['change_amount'],
                    ],
                    'Invoice: ' . $transaction->invoice_no
                );

                return response()->json([
                    'success' => true,
                    'transaction_id' => $transaction->id,
                    'invoice_no' => $transaction->invoice_no,
                    'change' => $data['change_amount']
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . implode(', ', $e->errors()[array_key_first($e->errors())])], 422);
        } catch (\Exception $e) {
            Log::error('POS Checkout Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hold current transaction
     */
    public function holdTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        try {
            $held = HeldTransaction::create([
                'user_id' => Auth::id(),
                'items' => $request->items,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            return response()->json(['success' => true, 'held_id' => $held->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get held transactions
     */
    public function getHeldTransactions()
    {
        $held = HeldTransaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $held]);
    }

    /**
     * Resume held transaction
     */
    public function resumeHeldTransaction($id)
    {
        $held = HeldTransaction::findOrFail($id);

        // Return the held transaction data
        $data = [
            'items' => $held->items,
            // customer_id removed
            'subtotal' => $held->subtotal,
            'discount' => $held->discount,
            'total' => $held->total,
        ];

        // Delete the held transaction
        $held->delete();

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Delete held transaction
     */
    public function deleteHeldTransaction($id)
    {
        $held = HeldTransaction::findOrFail($id);
        $held->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Print receipt for a transaction
     */
    public function printReceipt($transactionId)
    {
        $transaction = Transaction::with(['user', 'items.product'])
            ->findOrFail($transactionId);

        $paperSize = \App\Models\Setting::get('receipt_paper_size', '58mm');

        return view('pos.receipt', compact('transaction', 'paperSize'));
    }
}


