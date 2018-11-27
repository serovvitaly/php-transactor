<?php

namespace App\Transaction;

use App\Entity\Account;
use App\Entity\Exception\SenderBalanceIsEmptyException;
use App\Service\Exception\MoneyTransferTransactionException;
use App\ValueObject\Money;

interface MoneyTransferTransactionInterface
{
    /**
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws MoneyTransferTransactionException
     * @throws SenderBalanceIsEmptyException
     */
    public function execute(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): void;
}
