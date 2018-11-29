<?php

namespace PhpTransactor\Repository;

use PhpTransactor\Entity\Account;
use PhpTransactor\Identifier\AccountIdentifier;

interface AccountRepositoryInterface
{
    public function findOrMakeByIdentifier(AccountIdentifier $identifier): Account;

    public function persist(Account $account): void;
}
