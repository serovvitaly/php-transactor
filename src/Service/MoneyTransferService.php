<?php

namespace PhpTransactor\Service;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Exception\SenderBalanceIsEmptyException;
use PhpTransactor\Service\Exception\AttemptTransferZeroAmountException;
use PhpTransactor\Service\Exception\CurrenciesMismatchMoneyTransferException;
use PhpTransactor\Service\Exception\MoneyTransferBetweenSameAccountException;
use PhpTransactor\Service\Exception\TransactionException;
use PhpTransactor\Service\Exception\ValidationException;
use PhpTransactor\Transaction\MoneyTransferTransactionInterface;
use PhpTransactor\Validator\MoneyTransferValidatorInterface;
use PhpTransactor\ValueObject\Money;

class MoneyTransferService implements MoneyTransferServiceInterface
{
    /** @var MoneyTransferValidatorInterface */
    private $moneyTransferValidator;
    /** @var MoneyTransferTransactionInterface */
    private $moneyTransferTransaction;

    public function __construct(
        MoneyTransferValidatorInterface $moneyTransferValidator,
        MoneyTransferTransactionInterface $moneyTransferTransaction
    ) {
        $this->moneyTransferValidator = $moneyTransferValidator;
        $this->moneyTransferTransaction = $moneyTransferTransaction;
    }

    /**
     * @inheritdoc
     */
    public function performMoneyTransfer(
        Account $senderAccount,
        Account $recipientAccount,
        Money $transferringMoney
    ): void {

        try {
            $this->moneyTransferValidator->validate(
                $senderAccount,
                $recipientAccount,
                $transferringMoney
            );
        } catch (AttemptTransferZeroAmountException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode(), $e);
        } catch (MoneyTransferBetweenSameAccountException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode(), $e);
        } catch (CurrenciesMismatchMoneyTransferException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $this->moneyTransferTransaction->execute(
                $senderAccount,
                $recipientAccount,
                $transferringMoney
            );
        } catch (SenderBalanceIsEmptyException $e) {
            throw new TransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (CurrenciesMismatchMoneyTransferException $e) {
            throw new TransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception\MoneyTransferTransactionException $e) {
            throw new TransactionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
