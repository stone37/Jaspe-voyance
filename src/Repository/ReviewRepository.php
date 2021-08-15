<?php

namespace App\Repository;

use App\Entity\Review;
use App\Model\Admin\ReviewSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getEnabled()
    {
        return $this->createQueryBuilder('r')
                ->where('r.enabled = 1')
                ->orderBy('r.createdAt', 'desc');
    }

    public function getAdminReviews(ReviewSearch $search)
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'desc');

        if ($search->isEnabled())
            $qb->andWhere('r.enabled = 1');

        if ($search->isHome())
            $qb->andWhere('r.home = 1');

        return $qb;
    }

    public function getHomeReviews()
    {
        $qb = $this->createQueryBuilder('r')
                ->where('r.enabled = 1')
                ->andWhere('r.home = 1')
                ->orderBy('r.createdAt', 'desc')
                ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    public function getNumber()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.enabled = 1');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }


    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
