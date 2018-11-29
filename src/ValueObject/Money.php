<?php

namespace PhpTransactor\ValueObject;

use PhpTransactor\Identifier\CurrencyIdentifier;
use PhpTransactor\ValueObject\Exception\NegativeMoneyValueException;

class Money
{
    private $currency;
    private $value;

    /**
     * Money constructor.
     * @param CurrencyIdentifier $currency
     * @param int $valueAsMinorUnits
     * @throws NegativeMoneyValueException
     */
    public function __construct(CurrencyIdentifier $currency, int $valueAsMinorUnits)
    {
        if ($valueAsMinorUnits < 0) {
            throw new NegativeMoneyValueException();
        }

        $this->currency = $currency;
        $this->value = $valueAsMinorUnits;
    }

    /**
     * Возвращает сущность Валюты
     * @return CurrencyIdentifier
     */
    public function getCurrencyIdentifier(): CurrencyIdentifier
    {
        return $this->currency;
    }

    /**
     * Возвращает значение в основных денежных еденицах
     * @return float
     */
    public function getValueAsMajorUnits(): float
    {
        return $this->value / 100;
    }

    /**
     * Возвращает значение в разменных денежных еденицах
     * @return int
     */
    public function getValueAsMinorUnits(): int
    {
        return $this->value;
    }
}
