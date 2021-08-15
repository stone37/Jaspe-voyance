<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\Admin\UserSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUser($id)
    {
        try
        {
            $qb = $this->createQueryBuilder('u');

            $qb = $qb->where($qb->expr()->isNull('u.deleted'))
                ->andWhere('u.id = :id')
                ->andWhere('u.enabled = 1')
                ->setParameter('id', (int)$id)
                ->getQuery()->getOneOrNullResult();

        } catch (NonUniqueResultException $exception) {
            sprintf("Exception: %s", $exception->getMessage());
        }

        return $qb;
    }

    public function getUserNumber()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id)');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.enabled = 1')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getAdminNumber()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id)');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%'."ROLE_ADMIN".'%');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    /**
     * @return QueryBuilder|null
     */
    public function getAdmins(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%'."ROLE_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->isEnabled())
            $qb->andWhere('u.enabled = 1');

        return $qb;
    }

    public function getUsers(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.enabled = 1')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.ville = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    public function getUserNoConfirmed(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.enabled = 0')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.ville = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    public function getUserDeleted(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNotNull('u.deleted'))
            ->andWhere('u.enabled = 0')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.ville = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    public function getLastClients()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.enabled = 1')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function getUserEmail()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email');

        $qb->where($qb->expr()->isNull('u.deleted'))
            ->andWhere('u.enabled = 1')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%');

        return $qb->getQuery()->getResult();
    }
}
