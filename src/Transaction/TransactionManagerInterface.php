<?php

namespace App\Transaction;

interface TransactionManagerInterface
{
    public function beginTransaction();
    public function commit();
    public function rollBack();
}
