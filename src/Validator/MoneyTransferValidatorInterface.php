<?php

namespace PhpTransactor\Validator;

use PhpTransactor\Entity\Account;
use PhpTransactor\Service\Exception\AttemptTransferZeroAmountException;
use PhpTransactor\Service\Exception\CurrenciesMismatchMoneyTransferException;
use PhpTransactor\Service\Exception\MoneyTransferBetweenSameAccountException;
use PhpTransactor\ValueObject\Money;

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
