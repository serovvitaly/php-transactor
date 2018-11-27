<?php

namespace App\Service\Exception;

use App\Identifier\AccountIdentifier;
use Throwable;

class AccountNotFoundException extends \Exception
{
    public function __construct(AccountIdentifier $accountIdentifier, int $code = 0, Throwable $previous = null)
    {
        $message = 'Account not found: ' . implode('-', (array)$accountIdentifier);

        parent::__construct($message, $code, $previous);
    }
}
