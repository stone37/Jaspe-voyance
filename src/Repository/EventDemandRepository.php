<?php

namespace App\Repository;

use App\Entity\EventDemand;
use App\Model\Admin\DemandSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventDemand|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventDemand|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventDemand[]    findAll()
 * @method EventDemand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventDemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventDemand::class);
    }

    public function getDemand(DemandSearch $search)
    {
        $qb = $this->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'desc');

        if ($search->isEnabled())
            $qb->andWhere('d.enabled = 1');

        return $qb;
    }

    // /**
    //  * @return Demand[] Returns an array of Demand objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demand
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
