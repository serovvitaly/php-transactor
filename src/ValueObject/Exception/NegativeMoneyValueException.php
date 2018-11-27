<?php

namespace App\ValueObject\Exception;

/**
 * Исключение "Отрацательно денежное значение"
 * @package App\ValueObject\Exception
 */
class NegativeMoneyValueException extends \Exception
{
    protected $message = 'Negative money value';
}
