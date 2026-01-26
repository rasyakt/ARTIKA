<?php

namespace App\Services;

use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Stock;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService
{
    protected $transactionRepository;
    protected $productRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->productRepository = $productRepository;
    }

    public function processTransaction(array $data, array $items)
    {
        // 0. Preliminary Stock Check (Prevent negative stock)
        foreach ($items as $item) {
            $stock = Stock::where('product_id', $item['product_id'])->first();
            $available = $stock ? $stock->quantity : 0;

            if ($available < $item['quantity']) {
                $product = Product::find($item['product_id']);
                $productName = $product ? $product->name : 'Unknown Product';
                throw new \Exception(trans('pos.insufficient_stock') . " ({$productName}. Tersedia: {$available}, Diminta: {$item['quantity']})");
            }
        }

        // Control Transaction
        return DB::transaction(function () use ($data, $items) {
            // 1. Create Transaction Header
            $data['invoice_no'] = 'INV-' . strtoupper(Str::random(10)); // Simple invoice gen
            $data['status'] = 'completed';

            $transaction = $this->transactionRepository->createTransaction($data);

            // 2. Process Items & Deduct Stock
            foreach ($items as $item) {
                // Get current stock for logging
                $stock = Stock::where('product_id', $item['product_id'])->first();
                $qtyBefore = $stock ? $stock->quantity : 0;

                // Deduct Stock
                $this->productRepository->updateStock($item['product_id'], $item['quantity']);

                $newStock = Stock::where('product_id', $item['product_id'])->first();
                $qtyAfter = $newStock ? $newStock->quantity : 0;

                // Log Stock Movement (OUT)
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity_before' => $qtyBefore,
                    'quantity_after' => $qtyAfter,
                    'quantity_change' => -$item['quantity'],
                    'reason' => 'POS Sale',
                    'reference' => $transaction->invoice_no
                ]);

                $item['transaction_id'] = $transaction->id;
                $item['subtotal'] = $item['quantity'] * $item['price'];

                $this->transactionRepository->createTransactionItem($item);
            }

            // 3. Accounting Integration (Simple Double Entry)
            // Debit Application: Cash
            \App\Models\Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'debit',
                'account_name' => 'Cash',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            // Credit Application: Sales Revenue
            \App\Models\Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'credit',
                'account_name' => 'Sales Revenue',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            return $transaction;
        });
    }
}
