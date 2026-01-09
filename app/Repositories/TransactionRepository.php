<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\TransactionItem;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function createTransaction(array $data)
    {
        return Transaction::create($data);
    }

    public function createTransactionItem(array $data)
    {
        return TransactionItem::create($data);
    }
}
