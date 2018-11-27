<?php

namespace App\Service;

use App\Entity\Account;
use App\ValueObject\Money;

interface MoneyTransferServiceInterface
{
    /**
     * Совершает денежный перевод со счета отправителя на счет получателя
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws Exception\ValidationException
     * @throws Exception\TransactionException
     */
    public function performMoneyTransfer(
        Account $senderAccount,
        Account $recipientAccount,
        Money $transferringMoney
    ): void;
}
