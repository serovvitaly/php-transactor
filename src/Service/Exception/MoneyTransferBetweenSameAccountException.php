<?php

namespace App\Service\Exception;

/**
 * Исключение "Невозможно перевести деньги между одним счетом"
 * @package App\Service\Exception
 */
class MoneyTransferBetweenSameAccountException extends \Exception
{
    protected $message = 'Impossible transfer money between same account';
}
