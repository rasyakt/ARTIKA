<?php

namespace App\Interfaces;

interface TransactionRepositoryInterface
{
    public function createTransaction(array $data);
    public function createTransactionItem(array $data);
}
