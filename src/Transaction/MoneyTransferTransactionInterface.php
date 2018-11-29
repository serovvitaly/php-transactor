<?php

namespace PhpTransactor\Transaction;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Exception\SenderBalanceIsEmptyException;
use PhpTransactor\Service\Exception\MoneyTransferTransactionException;
use PhpTransactor\ValueObject\Money;

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
