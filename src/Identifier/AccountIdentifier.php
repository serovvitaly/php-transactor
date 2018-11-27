<?php

namespace App\Identifier;

/**
 * Идентификатор сущности "Лицевой счет"
 * @package App\Identifier
 */
class AccountIdentifier
{
    const TYPE = 'account';

    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * Проверяет, совпадает ли текущий идентификатор с переданным
     * @param AccountIdentifier $accountIdentifier
     * @return bool
     */
    public function isA(AccountIdentifier $accountIdentifier): bool
    {
        return $this->id === $accountIdentifier->getId();
    }
}
