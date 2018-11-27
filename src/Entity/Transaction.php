<?php

namespace App\Entity;

/**
 *
 */
class Transaction
{
    const PROCESS_STATUS = 1;
    const SUCCESS_STATUS = 2;
    const FAILURE_STATUS = 3;

    private $id;

    private $sender_account_id;

    private $recipient_account_id;

    private $money_minor_units;

    private $status_code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderAccountId(): ?string
    {
        return $this->sender_account_id;
    }

    public function setSenderAccountId(string $sender_account_id): self
    {
        $this->sender_account_id = $sender_account_id;

        return $this;
    }

    public function getRecipientAccountId(): ?string
    {
        return $this->recipient_account_id;
    }

    public function setRecipientAccountId(string $recipient_account_id): self
    {
        $this->recipient_account_id = $recipient_account_id;

        return $this;
    }

    public function getMoneyMinorUnits(): ?int
    {
        return $this->money_minor_units;
    }

    public function setMoneyMinorUnits(int $money_minor_units): self
    {
        $this->money_minor_units = $money_minor_units;

        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->status_code = $statusCode;
        return $this;
    }

    public function setProcessStatus(): self
    {
        $this->status_code = self::PROCESS_STATUS;
        return $this;
    }

    public function setSuccessStatus(): self
    {
        $this->status_code = self::SUCCESS_STATUS;
        return $this;
    }

    public function setFailureStatus(): self
    {
        $this->status_code = self::FAILURE_STATUS;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->status_code;
    }
}
