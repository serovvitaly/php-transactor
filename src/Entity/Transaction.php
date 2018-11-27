<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(
 *     name="transaction",
 *     indexes={
 *         @ORM\Index(name="sender_account_idx", columns={"sender_account_id"}),
 *         @ORM\Index(name="recipient_account_idx", columns={"recipient_account_id"})
 *     }
 * )
 */
class Transaction
{
    const PROCESS_STATUS = 1;
    const SUCCESS_STATUS = 2;
    const FAILURE_STATUS = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $sender_account_id;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $recipient_account_id;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $money_minor_units;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
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

    public function postPersist($entity, $event)
    {
        var_dump($entity, $event);
    }

    public function postUpdate($entity, $event)
    {
        var_dump($entity, $event);
    }
}
