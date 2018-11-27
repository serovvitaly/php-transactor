<?php

namespace App\Tests\App\Validator;

use App\Entity\Account;
use App\Identifier\AccountIdentifier;
use App\Identifier\CurrencyIdentifier;
use App\Repository\AccountRepositoryInterface;
use App\Service\Exception\AttemptTransferZeroAmountException;
use App\Service\Exception\CurrenciesMismatchMoneyTransferException;
use App\Service\Exception\MoneyTransferBetweenSameAccountException;
use App\Validator\MoneyTransferValidator;
use App\ValueObject\Exception\NegativeMoneyValueException;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTransferValidatorTest extends TestCase
{
    protected function getAccount(string $id)
    {
        $account = new Account();
        $account->setId($id);
        $account->setBalance(10);
        return $account;
    }

    /**
     * @throws AttemptTransferZeroAmountException
     * @throws CurrenciesMismatchMoneyTransferException
     * @throws MoneyTransferBetweenSameAccountException
     * @throws NegativeMoneyValueException
     */
    public function testValidate()
    {
        $senderAccount = $this->getAccount(1002001000200000 . 9850);
        $recipientAccount = $this->getAccount(2002001000200000 . 9850);
        $money = new Money(new CurrencyIdentifier(9850), 1);

        $moneyTransferValidator = new MoneyTransferValidator();
        $moneyTransferValidator->validate($senderAccount, $recipientAccount, $money);

        $this->assertTrue(true);
    }

    /**
     * Проверка положительности переводимой суммы
     * @throws AttemptTransferZeroAmountException
     * @throws NegativeMoneyValueException
     * @throws CurrenciesMismatchMoneyTransferException
     * @throws MoneyTransferBetweenSameAccountException
     */
    public function testAttemptTransferZeroAmountException()
    {
        $senderAccount = $this->getAccount(1002001000200000 . 9850);
        $recipientAccount = $this->getAccount(2002001000200000 . 9850);
        $money = new Money(new CurrencyIdentifier(9850), 0);

        $moneyTransferValidator = new MoneyTransferValidator();

        $this->expectException(AttemptTransferZeroAmountException::class);
        $moneyTransferValidator->validate($senderAccount, $recipientAccount, $money);
    }

    /**
     * Проверка неидентичности аккаунтов отправителя и получателя
     * @throws AttemptTransferZeroAmountException
     * @throws CurrenciesMismatchMoneyTransferException
     * @throws MoneyTransferBetweenSameAccountException
     * @throws NegativeMoneyValueException
     */
    public function testMoneyTransferBetweenSameAccountException()
    {
        $senderAccount = $this->getAccount(1002001000200000 . 9850);
        $recipientAccount = $this->getAccount(1002001000200000 . 9850);
        $money = new Money(new CurrencyIdentifier(9850), 1);

        $moneyTransferValidator = new MoneyTransferValidator();

        $this->expectException(MoneyTransferBetweenSameAccountException::class);
        $moneyTransferValidator->validate($senderAccount, $recipientAccount, $money);
    }

    /**
     * Проверка соответствия валют аккаунтов отправителя и получателя
     * Проверка соответствия валют аккаунта отправителя и переводимых денег
     * @dataProvider currenciesMismatchMoneyTransferExceptionDataProvider
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $money
     * @throws AttemptTransferZeroAmountException
     * @throws CurrenciesMismatchMoneyTransferException
     * @throws MoneyTransferBetweenSameAccountException
     */
    public function testCurrenciesMismatchMoneyTransferException(
        Account $senderAccount,
        Account $recipientAccount,
        Money $money
    ) {
        $moneyTransferValidator = new MoneyTransferValidator();

        $this->expectException(CurrenciesMismatchMoneyTransferException::class);
        $moneyTransferValidator->validate($senderAccount, $recipientAccount, $money);
    }

    /**
     * @return array
     * @throws NegativeMoneyValueException
     */
    public function currenciesMismatchMoneyTransferExceptionDataProvider() {

        return [
            [
                /** Проверка соответствия валют аккаунтов отправителя и получателя */
                $senderAccount = $this->getAccount(1002001000200000 . 9850),
                $recipientAccount = $this->getAccount(2002001000200000 . 9851),
                $money = new Money(new CurrencyIdentifier(9850), 1)
            ], [
                /** Проверка соответствия валют аккаунта отправителя и переводимых денег */
                $senderAccount = $this->getAccount(1002001000200000 . 9850),
                $recipientAccount = $this->getAccount(2002001000200000 . 9850),
                $money = new Money(new CurrencyIdentifier(9851), 1)
            ],
        ];
    }
}
