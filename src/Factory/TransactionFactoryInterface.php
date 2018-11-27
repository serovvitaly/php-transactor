<?php

namespace App\Factory;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;

interface TransactionFactoryInterface
{
    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction;
}
