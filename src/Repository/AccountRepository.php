<?php

namespace App\Repository;

use App\Entity\Account;
use App\Identifier\AccountIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param AccountIdentifier $identifier
     * @return Account
     */
    public function findOrMakeByIdentifier(AccountIdentifier $identifier): Account
    {
        try {
            $account = $this->find($identifier->getId());
            if (!$account) {
                $account = $this->makeById($identifier->getId());
            }
            return $account;
        } catch (ORMException $e) {
            //
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * @param string $id
     * @return Account
     */
    private function makeById(string $id): Account
    {
        $account = new Account();
        $account->setId($id);
        $account->setBalance(0);
        return $account;
    }

    /**
     * @param Account $account
     * @throws ORMException
     */
    public function persist(Account $account): void
    {
        $this->getEntityManager()->persist($account);
    }
}
