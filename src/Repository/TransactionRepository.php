<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use App\ValueObject\Money;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository implements TransactionRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @param Transaction $transaction
     * @throws ORMException
     */
    public function persist(Transaction $transaction): void
    {
        $this->getEntityManager()->persist($transaction);
    }

    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction
    {
        $moneyTransferTransaction = new Transaction();
        $moneyTransferTransaction->setSenderAccountId($senderAccount->getId());
        $moneyTransferTransaction->setRecipientAccountId($recipientAccount->getId());
        $moneyTransferTransaction->setMoneyMinorUnits($transferringMoney->getValueAsMinorUnits());
        $moneyTransferTransaction->setProcessStatus();
        return $moneyTransferTransaction;
    }

    public function postPersist($entity, $event)
    {
        var_dump($entity, $event);
    }

    public function postUpdate($entity, $event)
    {
        var_dump($entity, $event);
    }
}
