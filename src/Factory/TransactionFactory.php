<?php

namespace App\Factory;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;

class TransactionFactory implements TransactionFactoryInterface
{
    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction
    {
        return new Transaction($senderAccount, $recipientAccount, $transferringMoney);
    }
}
