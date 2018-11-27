<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Exception\SenderBalanceIsEmptyException;
use App\Service\Exception\AttemptTransferZeroAmountException;
use App\Service\Exception\CurrenciesMismatchMoneyTransferException;
use App\Service\Exception\MoneyTransferBetweenSameAccountException;
use App\Service\Exception\TransactionException;
use App\Service\Exception\ValidationException;
use App\Transaction\MoneyTransferTransactionInterface;
use App\Validator\MoneyTransferValidatorInterface;
use App\ValueObject\Money;

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
