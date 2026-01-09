<?php

namespace App\Services;

use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
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
        return DB::transaction(function () use ($data, $items) {
            // 1. Create Transaction Header
            $data['invoice_no'] = 'INV-' . strtoupper(Str::random(10)); // Simple invoice gen
            $data['status'] = 'completed';

            $transaction = $this->transactionRepository->createTransaction($data);

            // 2. Process Items & Deduct Stock
            foreach ($items as $item) {
                // Deduct Stock
                $this->productRepository->updateStock($item['product_id'], $item['quantity']);

                $item['transaction_id'] = $transaction->id;
                $item['subtotal'] = $item['quantity'] * $item['price'];

                $this->transactionRepository->createTransactionItem($item);
            }

            // 3. Accounting Integration (Simple Double Entry)
            // Debit Application: Cash
            \App\Models\Journal::create([
                'transaction_id' => $transaction->id,
                'branch_id' => $data['branch_id'],
                'type' => 'debit',
                'account_name' => 'Cash',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            // Credit Application: Sales Revenue
            \App\Models\Journal::create([
                'transaction_id' => $transaction->id,
                'branch_id' => $data['branch_id'],
                'type' => 'credit',
                'account_name' => 'Sales Revenue',
                'amount' => $transaction->total_amount,
                'description' => 'Sales Invoice ' . $transaction->invoice_no,
            ]);

            return $transaction;
        });
    }
}
