<?php

namespace App\Factory;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;

class TransactionFactory implements TransactionFactoryInterface
{
    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction
    {
        $moneyTransferTransaction = new Transaction();
        $moneyTransferTransaction->setSenderAccountId($senderAccount->getId());
        $moneyTransferTransaction->setRecipientAccountId($recipientAccount->getId());
        $moneyTransferTransaction->setMoneyMinorUnits($transferringMoney->getValueAsMinorUnits());
        $moneyTransferTransaction->setProcessStatus();
        return $moneyTransferTransaction;
    }
}
