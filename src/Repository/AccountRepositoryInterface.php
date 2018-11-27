<?php

namespace App\Repository;

use App\Entity\Account;
use App\Identifier\AccountIdentifier;

interface AccountRepositoryInterface
{
    public function findOrMakeByIdentifier(AccountIdentifier $identifier): Account;

    public function persist(Account $account): void;
}
