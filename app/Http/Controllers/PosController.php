<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeldTransaction;
use App\Models\AuditLog;

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
        $categories = \App\Models\Category::all();
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
        $heldTransactions = HeldTransaction::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pos.index', compact('products', 'categories', 'paymentMethods', 'heldTransactions'));
    }

    public function scanner()
    {
        $products = $this->productRepository->getAllProducts();
        return view('pos.scanner', compact('products'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // Validation
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'cash_amount' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $data = [
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'cash_amount' => $validated['cash_amount'] ?? $validated['total_amount'],
                'change_amount' => $validated['change_amount'] ?? 0,
            ];

            $items = $validated['items'];

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . implode(', ', $e->errors()[array_key_first($e->errors())])], 422);
        } catch (\Exception $e) {
            \Log::error('POS Checkout Error: ' . $e->getMessage(), ['exception' => $e]);
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
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
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
        $held = HeldTransaction::where('user_id', \Illuminate\Support\Facades\Auth::id())
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
        $transaction = \App\Models\Transaction::with(['user', 'items.product'])
            ->findOrFail($transactionId);

        return view('pos.receipt', compact('transaction'));
    }
}


