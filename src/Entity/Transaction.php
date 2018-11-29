<?php

namespace PhpTransactor\Entity;

use PhpTransactor\ValueObject\Money;

/**
 *
 */
class Transaction
{
    const PROCESS_STATUS = 1;
    const SUCCESS_STATUS = 2;
    const FAILURE_STATUS = 3;

    private $id;

    private $statusCode;

    /** @var Account */
    private $senderAccount;
    /** @var Account */
    private $recipientAccount;
    /** @var Money */
    private $transferredMoney;

    public function __construct(Account $senderAccount, Account $recipientAccount, Money $transferredMoney)
    {
        $this->senderAccount = $senderAccount;
        $this->recipientAccount = $recipientAccount;
        $this->transferredMoney = $transferredMoney;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSenderAccountId(): string
    {
        return $this->senderAccount->getId();
    }

    public function getRecipientAccountId(): string
    {
        return $this->recipientAccount->getId();
    }

    public function getMoneyMinorUnits(): int
    {
        return $this->transferredMoney->getValueAsMinorUnits();
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setProcessStatus(): void
    {
        $this->statusCode = self::PROCESS_STATUS;
    }

    public function setSuccessStatus(): void
    {
        $this->statusCode = self::SUCCESS_STATUS;
    }

    public function setFailureStatus(): void
    {
        $this->statusCode = self::FAILURE_STATUS;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
