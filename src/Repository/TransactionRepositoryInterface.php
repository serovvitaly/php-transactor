<?php

namespace App\Repository;

use App\Entity\Transaction;

interface TransactionRepositoryInterface
{
    public function persist(Transaction $transaction): void;
}
