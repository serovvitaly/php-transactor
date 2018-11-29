<?php

namespace PhpTransactor\Service\Exception;

/**
 * Исключение "Несоответствие валют денежного перевода"
 * @package App\Service\Exception
 */
class CurrenciesMismatchMoneyTransferException extends \Exception
{
    protected $message = 'Currencies mismatch money transfer';
}
