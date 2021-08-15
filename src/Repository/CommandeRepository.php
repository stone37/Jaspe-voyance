<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findOneById($id): ?Commande
    {
        try {
            $qb = $this->createQueryBuilder('c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }

        return $qb;
    }

    public function getUserOrderActiveNumber(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        $qb->andWhere($qb->expr()->isNotNull('c.validate'));

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getUserOrderNumber(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        $qb->andWhere($qb->expr()->isNull('c.validate'));

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getUserOrderActive(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->addSelect('user');

        $qb = $qb->where($qb->expr()->isNotNull('c.validate'))
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.createdAt', 'desc');

        return $qb;
    }

    public function getUserOrder(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->addSelect('user');

        $qb = $qb->where($qb->expr()->isNull('c.validate'))
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.createdAt', 'desc');

        return $qb;
    }

    public function getNumber()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.validate = 1');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getLastOrders()
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.validate = 1')
            ->orderBy('c.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
