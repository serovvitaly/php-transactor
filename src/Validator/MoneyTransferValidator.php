<?php

namespace PhpTransactor\Validator;

use PhpTransactor\Entity\Account;
use PhpTransactor\Service\Exception\AttemptTransferZeroAmountException;
use PhpTransactor\Service\Exception\CurrenciesMismatchMoneyTransferException;
use PhpTransactor\Service\Exception\MoneyTransferBetweenSameAccountException;
use PhpTransactor\ValueObject\Money;

class MoneyTransferValidator implements MoneyTransferValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): void
    {
        /** Проверка положительности переводимой суммы */
        if ($transferringMoney->getValueAsMinorUnits() <= 0) {
            throw new AttemptTransferZeroAmountException();
        }

        /** Проверка неидентичности аккаунтов отправителя и получателя */
        if ($senderAccount->isA($recipientAccount)) {
            throw new MoneyTransferBetweenSameAccountException();
        }

        /** Проверка соответствия валют аккаунтов отправителя и получателя */
        if (!$senderAccount->getCurrencyIdentifier()->isA($recipientAccount->getCurrencyIdentifier())) {
            throw new CurrenciesMismatchMoneyTransferException();
        }

        /** Проверка соответствия валют аккаунта отправителя и переводимых денег */
        if (!$senderAccount->getCurrencyIdentifier()->isA($transferringMoney->getCurrencyIdentifier())) {
            throw new CurrenciesMismatchMoneyTransferException();
        }
    }
}
