<?php

namespace PhpTransactor\Repository;

use PhpTransactor\Entity\Transaction;

interface TransactionRepositoryInterface
{
    public function persist(Transaction $transaction): void;
}
