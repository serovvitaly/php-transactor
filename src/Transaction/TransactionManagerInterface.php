<?php

namespace PhpTransactor\Transaction;

interface TransactionManagerInterface
{
    public function beginTransaction();
    public function commit();
    public function rollBack();
}
