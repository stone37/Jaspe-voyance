<?php

namespace App\Repository;

use App\Entity\Category;
use App\Model\Admin\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getCategory()
    {
        $results = $this->createQueryBuilder('c')
                        ->orderBy('c.name', 'asc')
                        ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['id'];

        return $data;
    }

    public function getAdminCategories(CategorySearch $search)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'desc');

        if ($search->getName())
            $qb->andWhere('c.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');

        return $qb;
    }

    public function getBySlug($slug)
    {
        $qb = $this->createQueryBuilder('c')
                ->where('c.slug = :slug')
                ->orderBy('c.createdAt', 'desc')
                ->setParameter('slug', $slug);

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
