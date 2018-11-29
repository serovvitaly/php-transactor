<?php

namespace PhpTransactor\Factory;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Transaction;
use PhpTransactor\ValueObject\Money;

interface TransactionFactoryInterface
{
    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction;
}
