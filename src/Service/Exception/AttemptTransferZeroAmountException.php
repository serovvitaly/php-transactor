<?php

namespace PhpTransactor\Service\Exception;

/**
 * Исключение "Попытка перевода нулевой суммы"
 * @package App\Service\Exception
 */
class AttemptTransferZeroAmountException extends \Exception
{
    protected $message = 'Attempt transfer zero amount';
}
