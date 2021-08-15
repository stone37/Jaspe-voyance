<?php

namespace App\Repository;

use App\Entity\Voyance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voyance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voyance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voyance[]    findAll()
 * @method Voyance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoyanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyance::class);
    }

    // /**
    //  * @return Voyance[] Returns an array of Voyance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voyance
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
