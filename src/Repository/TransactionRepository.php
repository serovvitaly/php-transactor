<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @param Transaction $transaction
     */
    public function persist(Transaction $transaction): void
    {
        $this->getEntityManager()->persist($transaction);
    }
}
