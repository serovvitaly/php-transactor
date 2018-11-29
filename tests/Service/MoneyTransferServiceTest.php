<?php

namespace PhpTransactor\Tests\Service;

use PhpTransactor\Entity\Account;
use PhpTransactor\Factory\TransactionFactory;
use PhpTransactor\Identifier\CurrencyIdentifier;
use PhpTransactor\Repository\AccountRepositoryInterface;
use PhpTransactor\Repository\TransactionRepositoryInterface;
use PhpTransactor\Service\Exception\TransactionException;
use PhpTransactor\Service\Exception\ValidationException;
use PhpTransactor\Service\MoneyTransferService;
use PhpTransactor\Service\MoneyTransferServiceInterface;
use PhpTransactor\Transaction\MoneyTransferTransaction;
use PhpTransactor\Transaction\TransactionManagerInterface;
use PhpTransactor\Validator\MoneyTransferValidator;
use PhpTransactor\ValueObject\Exception\NegativeMoneyValueException;
use PhpTransactor\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTransferServiceTest extends TestCase
{
    /** @var MoneyTransferServiceInterface */
    private $moneyTransferService;
    /** @var AccountRepositoryInterface */
    private $accountRepository;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $moneyTransferValidator = new MoneyTransferValidator();

        /** @var TransactionManagerInterface $transactionManager */
        $transactionManager = $this->createMock(TransactionManagerInterface::class);
        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->createMock(AccountRepositoryInterface::class);
        /** @var TransactionRepositoryInterface $transactionRepository */
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        /** @var TransactionFactory $transactionFactory */
        $transactionFactory = new TransactionFactory();

        $moneyTransferTransaction = new MoneyTransferTransaction(
            $transactionManager,
            $accountRepository,
            $transactionRepository,
            $transactionFactory
        );

        $this->moneyTransferService = new MoneyTransferService(
            $moneyTransferValidator,
            $moneyTransferTransaction
        );
    }

    protected function getAccount(string $id)
    {
        $account = new Account();
        $account->setId($id);
        if ($id !== 1003001000100000 . 9850) {
            $account->setBalance(100);
        }
        return $account;
    }

    /**
     * @throws NegativeMoneyValueException
     * @throws TransactionException
     * @throws ValidationException
     */
    public function testPerformMoneyTransfer()
    {
        $currency = new CurrencyIdentifier(9850);
        $senderAccount = $this->getAccount(1002001000200000 . $currency->getId());
        $recipientAccount = $this->getAccount(1001001000100000 . $currency->getId());
        $money = new Money($currency, 1);

        $senderAccountBalance = $senderAccount->getBalance();
        $recipientAccountBalance = $recipientAccount->getBalance();

        $this->moneyTransferService->performMoneyTransfer($senderAccount, $recipientAccount, $money);

        $this->assertEquals(
            $senderAccount->getBalance(),
            $senderAccountBalance - $money->getValueAsMinorUnits(),
            'Баланс отправителя должен уменьшиться на ' . $money->getValueAsMinorUnits()
        );

        $this->assertEquals(
            $recipientAccount->getBalance(),
            $recipientAccountBalance + $money->getValueAsMinorUnits(),
            'Баланс получателя должен увеличиться на ' . $money->getValueAsMinorUnits()
        );
    }

    /**
     * @dataProvider validationExceptionsDataProvider
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws TransactionException
     * @throws ValidationException
     */
    public function testValidationException(Account $senderAccount, Account $recipientAccount, Money $transferringMoney)
    {
        $this->expectException(ValidationException::class);
        $this->moneyTransferService->performMoneyTransfer($senderAccount, $recipientAccount, $transferringMoney);
    }

    /**
     * @return array
     * @throws NegativeMoneyValueException
     */
    public function validationExceptionsDataProvider()
    {
        return [
            [
                /** Перевод между одним счетом */
                $this->getAccount(1002001000200000 . 9850),
                $this->getAccount(1002001000200000 . 9850),
                new Money(new CurrencyIdentifier(9850), 1)
            ], [
                /** Сумма перевода равна 0 */
                $this->getAccount(1002001000200000 . 9850),
                $this->getAccount(2002001000100000 . 9850),
                new Money(new CurrencyIdentifier(9850), 0)
            ], [
                $this->getAccount(1002001000200000 . 9851),
                $this->getAccount(1002001000100000 . 9851),
                new Money(new CurrencyIdentifier(9850), 1)
            ],
            [
                $this->getAccount(1002001000200000 . 9851),
                $this->getAccount(1002001000100000 . 9850),
                new Money(new CurrencyIdentifier(9850), 1)
            ],
            [
                $this->getAccount(1002001000200000 . 9850),
                $this->getAccount(1002001000100000 . 9851),
                new Money(new CurrencyIdentifier(9850), 1)
            ],
            [
                $this->getAccount(1002001000200000 . 9852),
                $this->getAccount(1002001000100000 . 9851),
                new Money(new CurrencyIdentifier(9850), 1)
            ],
        ];
    }


    /**
     * @dataProvider transactionExceptionsDataProvider
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws TransactionException
     * @throws ValidationException
     */
    public function testTransactionException(Account $senderAccount, Account $recipientAccount, Money $transferringMoney)
    {
        $this->expectException(TransactionException::class);
        $this->moneyTransferService->performMoneyTransfer($senderAccount, $recipientAccount, $transferringMoney);
    }

    /**
     * @return array
     * @throws NegativeMoneyValueException
     */
    public function transactionExceptionsDataProvider()
    {
        $senderAccount = $this->getAccount(1003001000100000 . 9850);
        $senderAccount->setBalance(0);
        return [
            [
                /** Баланс отправителя пуст */
                $senderAccount,
                $this->getAccount(1002001000200000 . 9850),
                new Money(new CurrencyIdentifier(9850), 1)
            ],
        ];
    }
}
