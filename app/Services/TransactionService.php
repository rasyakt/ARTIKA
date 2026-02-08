<?php

namespace App\Services;

use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\ReturnTransaction;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Stock;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Journal;
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
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'debit',
                'account_name' => 'Cash',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            // Credit Application: Sales Revenue
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'credit',
                'account_name' => 'Sales Revenue',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            return $transaction;
        });
    }

    /**
     * Rollback a transaction
     */
    public function rollbackTransaction($transactionId)
    {
        $transaction = Transaction::with('items')->findOrFail($transactionId);

        if ($transaction->status === 'rolled_back') {
            throw new \Exception('Transaction is already rolled back.');
        }

        return DB::transaction(function () use ($transaction) {
            // 1. Restore Stock for each item
            foreach ($transaction->items as $item) {
                // Get current stock for logging
                $stock = Stock::where('product_id', $item->product_id)->first();
                $qtyBefore = $stock ? $stock->quantity : 0;

                // Restore Stock
                $this->productRepository->incrementStock($item->product_id, $item->quantity);

                $newStock = Stock::where('product_id', $item->product_id)->first();
                $qtyAfter = $newStock ? $newStock->quantity : 0;

                // Log Stock Movement (IN)
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity_before' => $qtyBefore,
                    'quantity_after' => $qtyAfter,
                    'quantity_change' => $item->quantity,
                    'reason' => 'Transaction Rollback',
                    'reference' => $transaction->invoice_no
                ]);
            }

            // 2. Accounting Integration (Reversed Entries)
            // Credit Cash
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'credit',
                'account_name' => 'Cash',
                'amount' => $transaction->total_amount,
                'description' => 'Rollback Sales Invoice ' . $transaction->invoice_no,
            ]);

            // Debit Sales Revenue
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'debit',
                'account_name' => 'Sales Revenue',
                'amount' => $transaction->total_amount,
                'description' => 'Rollback Sales Invoice ' . $transaction->invoice_no,
            ]);

            // 3. Update Transaction Status
            $transaction->update(['status' => 'rolled_back']);

            return $transaction;
        });
    }

    /**
     * Delete a transaction and restore stock if needed
     */
    public function deleteTransaction($transactionId)
    {
        return DB::transaction(function () use ($transactionId) {
            $transaction = Transaction::findOrFail($transactionId);

            // 1. Restore Stock if not already rolled back
            if ($transaction->status !== 'rolled_back') {
                foreach ($transaction->items as $item) {
                    // Get current stock for logging
                    $stock = Stock::where('product_id', $item->product_id)->first();
                    $qtyBefore = $stock ? $stock->quantity : 0;

                    // Restore Stock
                    $this->productRepository->incrementStock($item->product_id, $item->quantity);

                    $newStock = Stock::where('product_id', $item->product_id)->first();
                    $qtyAfter = $newStock ? $newStock->quantity : 0;

                    // Log Stock Movement (IN)
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'user_id' => Auth::id(),
                        'type' => 'in',
                        'quantity_before' => $qtyBefore,
                        'quantity_after' => $qtyAfter,
                        'quantity_change' => $item->quantity,
                        'reason' => 'Transaction Deletion (Stock Recovery)',
                        'reference' => $transaction->invoice_no
                    ]);
                }
            }

            // 2. Delete related records
            TransactionItem::where('transaction_id', $transactionId)->delete();
            Journal::where('transaction_id', $transactionId)->delete();

            // 3. Delete the transaction itself
            return $transaction->delete();
        });
    }

    /**
     * Process a return for a transaction
     *
     * @param int|Transaction $transactionId
     * @param array $items Array of ['product_id' => ID, 'quantity' => QTY]
     * @param string $reason
     * @return ReturnTransaction
     */
    public function processReturn($transactionId, array $items, string $reason)
    {
        $transaction = $transactionId instanceof Transaction
            ? $transactionId
            : Transaction::with('items.product')->findOrFail($transactionId);

        if ($transaction->status === 'rolled_back') {
            throw new \Exception('Cannot return items from a rolled back transaction.');
        }

        return DB::transaction(function () use ($transaction, $items, $reason) {
            $totalRefundAmount = 0;
            $returnItemsData = [];

            foreach ($items as $returnItem) {
                $originalItem = $transaction->items()
                    ->where('product_id', $returnItem['product_id'])
                    ->first();

                if (!$originalItem) {
                    throw new \Exception("Product ID {$returnItem['product_id']} not found in this transaction.");
                }

                // Calculate remaining quantity that can be returned
                $alreadyReturned = $transaction->getReturnedQuantity($returnItem['product_id']);
                $remainingQuantity = $originalItem->quantity - $alreadyReturned;

                if ($returnItem['quantity'] > $remainingQuantity) {
                    throw new \Exception("Return quantity ({$returnItem['quantity']}) exceeds remaining returnable quantity ({$remainingQuantity}) for {$originalItem->product->name}.");
                }

                // Calculate prorated refund if there was a discount
                // For simplicity, we use the literal unit price from the item
                $itemRefund = $returnItem['quantity'] * $originalItem->price;
                $totalRefundAmount += $itemRefund;

                // 1. Restore Stock
                $stock = Stock::where('product_id', $returnItem['product_id'])->first();
                $qtyBefore = $stock ? $stock->quantity : 0;

                $this->productRepository->incrementStock($returnItem['product_id'], $returnItem['quantity']);

                $newStock = Stock::where('product_id', $returnItem['product_id'])->first();
                $qtyAfter = $newStock ? $newStock->quantity : 0;

                // 2. Log Stock Movement (RETURN)
                StockMovement::create([
                    'product_id' => $returnItem['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity_before' => $qtyBefore,
                    'quantity_after' => $qtyAfter,
                    'quantity_change' => $returnItem['quantity'],
                    'reason' => 'Customer Return',
                    'reference' => $transaction->invoice_no
                ]);

                $returnItemsData[] = [
                    'product_id' => $returnItem['product_id'],
                    'product_name' => $originalItem->product->name,
                    'quantity' => $returnItem['quantity'],
                    'price' => $originalItem->price,
                    'subtotal' => $itemRefund
                ];
            }

            // 3. Create Return Record
            $returnRecord = ReturnTransaction::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'items' => $returnItemsData,
                'total_refund' => $totalRefundAmount,
                'reason' => $reason,
                'status' => 'approved', // Auto-approved for now
            ]);

            // 4. Accounting (Reverse Sales)
            // Debit Sales Revenue (Reducing Revenue)
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'debit',
                'account_name' => 'Sales Revenue',
                'amount' => $totalRefundAmount,
                'description' => 'Sales Return ' . $returnRecord->return_no . ' for Invoice ' . $transaction->invoice_no,
            ]);

            // Credit Cash (Giving money back)
            Journal::create([
                'transaction_id' => $transaction->id,
                'type' => 'credit',
                'account_name' => 'Cash',
                'amount' => $totalRefundAmount,
                'description' => 'Refund ' . $returnRecord->return_no . ' for Invoice ' . $transaction->invoice_no,
            ]);

            // 5. Update Transaction Status based on return amount
            // We use the computed total_refunded attribute which now includes the new returnRecord
            $totalRefunded = $transaction->total_refunded;

            if ($totalRefunded >= $transaction->total_amount) {
                $transaction->update(['status' => 'returned']);
            } elseif ($totalRefunded > 0) {
                $transaction->update(['status' => 'partial_return']);
            }

            return $returnRecord;
        });
    }
}
