<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;

interface TransactionRepositoryInterface
{
    public function persist(Transaction $transaction): void;

    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction;
}
