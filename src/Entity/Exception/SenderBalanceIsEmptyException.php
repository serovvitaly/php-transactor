<?php

namespace PhpTransactor\Entity\Exception;

/**
 * Исключение "Баланс отправителя пуст"
 * @package App\Entity\Exception
 */
class SenderBalanceIsEmptyException extends \Exception
{
    protected $message = 'Sender balance is empty';
}
