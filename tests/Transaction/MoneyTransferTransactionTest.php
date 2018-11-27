<?php

namespace App\Tests\App\Transaction;

use App\Entity\Account;
use App\Entity\Exception\SenderBalanceIsEmptyException;
use App\Identifier\AccountIdentifier;
use App\Identifier\CurrencyIdentifier;
use App\Repository\AccountRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use App\Service\Exception\MoneyTransferTransactionException;
use App\Transaction\MoneyTransferTransaction;
use App\ValueObject\Exception\NegativeMoneyValueException;
use App\ValueObject\Money;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class MoneyTransferTransactionTest extends TestCase
{
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var EntityManager */
    private $entityManager;
    /** @var MoneyTransferTransaction */
    private $moneyTransferTransaction;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        /** @var EntityManager $entityManager */
        $entityManager = '';
        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->createMock(AccountRepositoryInterface::class);
        /** @var TransactionRepositoryInterface $transactionRepository */
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);

        $this->moneyTransferTransaction = new MoneyTransferTransaction(
            $entityManager,
            $accountRepository,
            $transactionRepository
        );
    }

    protected function getAccount(string $id)
    {
        $account = new Account();
        $account->setId($id);
        $account->setBalance(10);
        return $account;
    }

    /**
     * @throws MoneyTransferTransactionException
     * @throws NegativeMoneyValueException
     * @throws SenderBalanceIsEmptyException
     */
    public function testExecute()
    {
        $senderAccount = $this->getAccount(1002001000200000 . 9850);
        $recipientAccount = $this->getAccount(2002001000200000 . 9850);
        $money = new Money(new CurrencyIdentifier(9850), 1);

        $this->moneyTransferTransaction->execute($senderAccount, $recipientAccount, $money);

        $this->assertTrue(true);
    }

    /**
     * Исключение "Баланс отправителя пуст"
     * @throws MoneyTransferTransactionException
     * @throws NegativeMoneyValueException
     * @throws SenderBalanceIsEmptyException
     */
    public function testSenderBalanceIsEmptyException()
    {
        $senderAccount = $this->getAccount(1002001000200000 . 9850);
        $recipientAccount = $this->getAccount(2002001000200000 . 9850);
        $money = new Money(new CurrencyIdentifier(9850), 11);

        $this->expectException(SenderBalanceIsEmptyException::class);
        $this->moneyTransferTransaction->execute($senderAccount, $recipientAccount, $money);
    }
}
