<?php

namespace App\Transaction;

use App\Entity\Account;
use App\Entity\Exception\SenderBalanceIsEmptyException;
use App\Entity\Transaction;
use App\Repository\AccountRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use App\Service\Exception\CurrenciesMismatchMoneyTransferException;
use App\Service\Exception\MoneyTransferTransactionException;
use App\Service\Exception\TransactionException;
use App\ValueObject\Money;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

class MoneyTransferTransaction implements MoneyTransferTransactionInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var TransactionRepositoryInterface */
    private $transactionRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        AccountRepositoryInterface $accountRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): void
    {
        $transaction = $this->make(
            $senderAccount,
            $recipientAccount,
            $transferringMoney
        );

        $this->transactionRepository->persist($transaction);

        $this->exec($transaction,
            $senderAccount,
            $recipientAccount,
            $transferringMoney
        );
    }

    /**
     * Создание транзакции со статусом "Progress"
     * @param $senderAccount
     * @param $recipientAccount
     * @param $transferringMoney
     * @return Transaction
     */
    private function make($senderAccount, $recipientAccount, $transferringMoney): Transaction
    {
        return $this->transactionRepository->make(
            $senderAccount,
            $recipientAccount,
            $transferringMoney
        );
    }

    /**
     * Стадия денежного перевода, сохранение транзакции со статусом "Success" или "Failure"
     * @param Transaction $transaction
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param Money $transferringMoney
     * @throws MoneyTransferTransactionException
     * @throws SenderBalanceIsEmptyException
     */
    private function exec(
        Transaction $transaction,
        Account $senderAccount,
        Account $recipientAccount,
        Money $transferringMoney
    ): void {

        $this->entityManager->getConnection()->beginTransaction();
        try {
            /** Снятие средств с аккаунта отправителя */
            $senderAccount->withdrawMoney($transferringMoney);
            $this->accountRepository->persist($senderAccount);

            /** Зачисление средств на аккаунт получателя */
            $recipientAccount->contributeMoney($transferringMoney);
            $this->accountRepository->persist($recipientAccount);

            /** Обновление статуса транзакции на "Success" */
            $transaction->setSuccessStatus();
            $this->transactionRepository->persist($transaction);

            $this->entityManager->getConnection()->commit();

        } catch (SenderBalanceIsEmptyException $e) {
            try {
                $this->entityManager->getConnection()->rollBack();
            } catch (ConnectionException $e) {
                throw new MoneyTransferTransactionException($e->getMessage(), $e->getCode(), $e);
            }
            throw $e;
        } catch (\Exception $e) {

            /** Обновление статуса транзакции на "Failure" */
            $transaction->setFailureStatus();
            $this->transactionRepository->persist($transaction);

            try {
                $this->entityManager->getConnection()->rollBack();
            } catch (ConnectionException $e) {
                throw new MoneyTransferTransactionException($e->getMessage(), $e->getCode(), $e);
            }
            throw new MoneyTransferTransactionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
