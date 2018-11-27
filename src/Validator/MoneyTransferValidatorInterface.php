<?php

namespace App\Validator;

use App\Entity\Account;
use App\Service\Exception\AttemptTransferZeroAmountException;
use App\Service\Exception\CurrenciesMismatchMoneyTransferException;
use App\Service\Exception\MoneyTransferBetweenSameAccountException;
use App\ValueObject\Money;

interface MoneyTransferValidatorInterface
{
    /**
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws AttemptTransferZeroAmountException
     * @throws CurrenciesMismatchMoneyTransferException
     * @throws MoneyTransferBetweenSameAccountException
     */
    public function validate(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): void;
}
