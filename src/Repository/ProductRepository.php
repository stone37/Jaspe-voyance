<?php

namespace App\Repository;

use App\Entity\Product;
use App\Model\Admin\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProducts(ProductSearch $search)
    {
        $qb = $this->createQueryBuilder('p');

        if ($search->getName())
            $qb->andWhere('p.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');

        if ($search->isEnabled())
            $qb->andWhere('p.enabled = 1');


        return $qb;
    }

    public function getProductEnabled()
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.enabled = 1')
            ->orderBy('p.name', 'ASC');

        return $qb;
    }

    public function getProduct($slug)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.enabled = 1')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug);

        try
        {
            $qb = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            sprintf("Exception: %s", $exception->getMessage());
        }

        return $qb;
    }

    public function findArray($array)
    {
        $qb = $this->createQueryBuilder('p')
            ->Where('p.id IN (:array)')
            ->setParameter('array', $array);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
