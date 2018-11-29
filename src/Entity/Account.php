<?php

namespace PhpTransactor\Entity;

use PhpTransactor\Entity\Exception\SenderBalanceIsEmptyException;
use PhpTransactor\Identifier\CurrencyIdentifier;
use PhpTransactor\ValueObject\Money;

/**
 * Сущность "Лицевой счет"
 */
class Account
{
    /** Режим "Разблокирован" */
    const UNLOCK_MODE = 1;
    /** Режим "Полностью заблокирован" */
    const FULL_LOCK_MODE = 2;
    /** Режим "Заблокирован для списаний" */
    const WITHDRAW_LOCK_MODE = 3;
    /** Режим "Заблокирован для зачислений" */
    const CONTRIBUTE_LOCK_MODE = 4;

    /**
     * Двадцатизначное целое положительное число, где:
     *   - первые 6 цифр - ID сети
     *   - вторые 10 цифр - ID клиента
     *   - третьи 4 цифры - ID валюты
     */
    private $id;

    /**
     * Баланс счета
     */
    private $balance;

    /**
     * Режим блокировки лицевого счета
     */
    private $lock_mode;

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Операция списания денег
     * @param Money $money
     * @throws SenderBalanceIsEmptyException
     */
    public function withdrawMoney(Money $money): void
    {
        if ($this->balance < $money->getValueAsMinorUnits()) {
            throw new SenderBalanceIsEmptyException();
        }
        $this->balance = $this->balance - $money->getValueAsMinorUnits();
    }

    /**
     * Операция зачисления денег
     * @param Money $money
     */
    public function contributeMoney(Money $money): void
    {
        $this->balance = $this->balance + $money->getValueAsMinorUnits();
    }

    public function setBalance(int $balanceAsMinorUnits): self
    {
        $this->balance = $balanceAsMinorUnits;
        return $this;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getCurrencyIdentifier(): CurrencyIdentifier
    {
        return new CurrencyIdentifier((int)substr($this->id, -4));
    }

    public function isA(Account $otherAccount): bool
    {
        return $this->id === $otherAccount->getId();
    }

    public function setLockmode(int $statusCode): self
    {
        $this->lock_mode = $statusCode;
        return $this;
    }

    public function unlocked()
    {
        $this->lock_mode = self::UNLOCK_MODE;
    }

    public function lockedFull()
    {
        $this->lock_mode = self::FULL_LOCK_MODE;
    }

    public function lockedWithdraw()
    {
        $this->lock_mode = self::WITHDRAW_LOCK_MODE;
    }

    public function lockedContribute()
    {
        $this->lock_mode = self::CONTRIBUTE_LOCK_MODE;
    }

    public function isUnlocked()
    {
        return $this->lock_mode === self::UNLOCK_MODE;
    }

    public function isFullLocked()
    {
        return $this->lock_mode === self::FULL_LOCK_MODE;
    }

    public function isWithdrawLocked()
    {
        return in_array($this->lock_mode, [self::FULL_LOCK_MODE, self::WITHDRAW_LOCK_MODE]);
    }

    public function isContributeLocked()
    {
        return in_array($this->lock_mode, [self::FULL_LOCK_MODE, self::CONTRIBUTE_LOCK_MODE]);
    }
}
