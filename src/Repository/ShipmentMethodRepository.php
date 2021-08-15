<?php

namespace App\Repository;

use App\Entity\ShipmentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShipmentMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentMethod[]    findAll()
 * @method ShipmentMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentMethod::class);
    }

    public function getMethod()
    {
        try
        {
            $qb = $this->createQueryBuilder('sm')
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            sprintf("Exception: %s", $exception->getMessage());
        }

        return $qb;
    }

    // /**
    //  * @return ShipmentMethod[] Returns an array of Shipment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shipment
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
