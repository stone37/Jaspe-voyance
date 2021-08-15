<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Model\Admin\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getTags()
    {
        $results = $this->createQueryBuilder('t')
            ->orderBy('t.name', 'asc')
            ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['id'];

        return $data;
    }

    public function getAdminTags(CategorySearch $search)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'desc');

        if ($search->getName())
            $qb->andWhere('c.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');

        return $qb;
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
