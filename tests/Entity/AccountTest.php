<?php

namespace PhpTransactor\Tests\Entity;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Exception\SenderBalanceIsEmptyException;
use PhpTransactor\Identifier\CurrencyIdentifier;
use PhpTransactor\ValueObject\Exception\NegativeMoneyValueException;
use PhpTransactor\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testUnlocked()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->unlocked();
        $this->assertTrue($account->isUnlocked());
        $this->assertFalse($account->isFullLocked());
        $this->assertFalse($account->isWithdrawLocked());
        $this->assertFalse($account->isContributeLocked());
    }

    public function testLockedFull()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->lockedFull();
        $this->assertTrue($account->isFullLocked());
        $this->assertTrue($account->isWithdrawLocked());
        $this->assertTrue($account->isContributeLocked());
        $this->assertFalse($account->isUnlocked());
    }

    public function testLockedWithdraw()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->lockedWithdraw();
        $this->assertTrue($account->isWithdrawLocked());
        $this->assertFalse($account->isFullLocked());
        $this->assertFalse($account->isContributeLocked());
        $this->assertFalse($account->isUnlocked());
    }

    public function testLockedContribute()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->lockedContribute();
        $this->assertTrue($account->isContributeLocked());
        $this->assertFalse($account->isFullLocked());
        $this->assertFalse($account->isWithdrawLocked());
        $this->assertFalse($account->isUnlocked());
    }

    public function testIsUnlocked()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->lockedFull();
        $this->assertFalse($account->isUnlocked());

        $account->lockedWithdraw();
        $this->assertFalse($account->isUnlocked());

        $account->lockedContribute();
        $this->assertFalse($account->isUnlocked());

        $account->unlocked();
        $this->assertTrue($account->isUnlocked());
    }

    public function testIsFullLocked()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->unlocked();
        $this->assertFalse($account->isFullLocked());

        $account->lockedWithdraw();
        $this->assertFalse($account->isFullLocked());

        $account->lockedContribute();
        $this->assertFalse($account->isFullLocked());

        $account->lockedFull();
        $this->assertTrue($account->isFullLocked());
    }

    public function testIsWithdrawLocked()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->unlocked();
        $this->assertFalse($account->isWithdrawLocked());

        $account->lockedWithdraw();
        $this->assertTrue($account->isWithdrawLocked());

        $account->lockedContribute();
        $this->assertFalse($account->isWithdrawLocked());

        $account->lockedFull();
        $this->assertTrue($account->isWithdrawLocked());
    }

    public function testIsContributeLocked()
    {
        $account = (new Account())->setId('10020010002000009850');

        $account->unlocked();
        $this->assertFalse($account->isContributeLocked());

        $account->lockedWithdraw();
        $this->assertFalse($account->isContributeLocked());

        $account->lockedContribute();
        $this->assertTrue($account->isContributeLocked());

        $account->lockedFull();
        $this->assertTrue($account->isContributeLocked());
    }

    public function testMarkAsRoot()
    {
        $account = new Account();
        $account->setId('10020010002000009850');
        $this->assertFalse($account->isRoot());

        $rootAccount = new Account();
        $rootAccount->setId('00000000000000009850');
        $this->assertTrue($rootAccount->isRoot());
    }

    /**
     * @throws NegativeMoneyValueException
     * @throws SenderBalanceIsEmptyException
     */
    public function testWithdrawMoney()
    {
        $moneyValue = 10;

        $money = new Money(new CurrencyIdentifier(9850), $moneyValue);

        $account = new Account();
        $account->setId('10020010002000009850');
        $account->contributeMoney($money);
        $account->withdrawMoney($money);
        $this->assertEquals(0, $account->getBalance());

        $rootAccount = new Account();
        $rootAccount->setId('00000000000000009850');
        $rootAccount->withdrawMoney($money);
        $this->assertEquals(0, $rootAccount->getBalance());
    }

    /**
     * @throws NegativeMoneyValueException
     */
    public function testContributeMoney()
    {
        $moneyValue = 10;

        $money = new Money(new CurrencyIdentifier(9850), $moneyValue);

        $account = new Account();
        $account->setId('10020010002000009850');
        $account->contributeMoney($money);
        $this->assertEquals($moneyValue, $account->getBalance());

        $rootAccount = new Account();
        $rootAccount->setId('00000000000000009850');
        $rootAccount->contributeMoney($money);
        $this->assertEquals(0, $rootAccount->getBalance());
    }

    /**
     * @throws NegativeMoneyValueException
     * @throws SenderBalanceIsEmptyException
     */
    public function testSenderBalanceIsEmptyException()
    {
        $money = new Money(new CurrencyIdentifier(9850), 10);

        $account = new Account();
        $account->setId('10020010002000009850');

        $this->expectException(SenderBalanceIsEmptyException::class);
        $account->withdrawMoney($money);
    }
}
