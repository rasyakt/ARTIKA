<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeldTransaction;
use App\Models\Customer;

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
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
        $customers = Customer::orderBy('name')->get();
        $heldTransactions = HeldTransaction::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pos.index', compact('products', 'paymentMethods', 'customers', 'heldTransactions'));
    }

    public function scanner()
    {
        $products = $this->productRepository->getAllProducts();
        return view('pos.scanner', compact('products'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // Validation
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'cash_amount' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'branch_id' => \Illuminate\Support\Facades\Auth::user()->branch_id,
            'customer_id' => $request->customer_id,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'cash_amount' => $request->cash_amount ?? $request->total_amount,
        ];

        $items = $request->items;

        try {
            $transaction = $this->transactionService->processTransaction($data, $items);
            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->invoice_no,
                'change' => $data['cash_amount'] - $data['total_amount']
            ]);
        } catch (\Exception $e) {
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
                'branch_id' => \Illuminate\Support\Facades\Auth::user()->branch_id,
                'customer_id' => $request->customer_id,
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
            'customer_id' => $held->customer_id,
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
        $transaction = \App\Models\Transaction::with(['user', 'branch', 'customer', 'items.product'])
            ->findOrFail($transactionId);

        return view('pos.receipt', compact('transaction'));
    }
}


