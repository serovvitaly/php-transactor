<?php

namespace PhpTransactor\Identifier;

class CurrencyIdentifier
{
    const TYPE = 'currency';

    /** @var string */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * Проверяет, совпадает ли текущий идентификатор с переданным
     * @param CurrencyIdentifier $currencyIdentifier
     * @return bool
     */
    public function isA(CurrencyIdentifier $currencyIdentifier): bool
    {
        return $this->id === $currencyIdentifier->getId();
    }
}
