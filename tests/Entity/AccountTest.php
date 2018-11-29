<?php

namespace PhpTransactor\Tests\Entity;

use PhpTransactor\Entity\Account;
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
}
