<?php

namespace PhpTransactor\Transaction;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Exception\SenderBalanceIsEmptyException;
use PhpTransactor\Entity\Transaction;
use PhpTransactor\Factory\TransactionFactoryInterface;
use PhpTransactor\Repository\AccountRepositoryInterface;
use PhpTransactor\Repository\TransactionRepositoryInterface;
use PhpTransactor\Service\Exception\MoneyTransferTransactionException;
use PhpTransactor\ValueObject\Money;

class MoneyTransferTransaction implements MoneyTransferTransactionInterface
{
    /** @var TransactionManagerInterface */
    private $transactionManager;
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var TransactionRepositoryInterface */
    private $transactionRepository;
    /** @var TransactionFactoryInterface */
    private $transactionFactory;

    public function __construct(
        TransactionManagerInterface $transactionManager,
        AccountRepositoryInterface $accountRepository,
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory
    ) {
        $this->transactionManager = $transactionManager;
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): void
    {
        $transaction = $this->transactionFactory->make(
            $senderAccount,
            $recipientAccount,
            $transferringMoney
        );
        $transaction->setProcessStatus();

        $this->transactionRepository->persist($transaction);

        $this->exec($transaction,
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

        $this->transactionManager->beginTransaction();
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

            $this->transactionManager->commit();

        } catch (SenderBalanceIsEmptyException $e) {
            $this->transactionManager->rollBack();
            throw $e;
        } catch (\Exception $e) {
            /** Обновление статуса транзакции на "Failure" */
            $transaction->setFailureStatus();
            $this->transactionRepository->persist($transaction);
            $this->transactionManager->rollBack();
            throw new MoneyTransferTransactionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
