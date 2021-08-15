<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Model\Admin\PostSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getPosts(): ?QueryBuilder
    {
        return $this->createQueryBuilder('p')
                    ->leftJoin('p.tags', 'tags')
                    ->leftJoin('p.categories', 'categories')
                    ->addSelect('tags')
                    ->addSelect('categories')
                    ->where('p.published = 1')
                    ->orderBy('p.publishedAt', 'desc');
    }

    public function getPost($slug)
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 'tags')
            ->leftJoin('p.categories', 'categories')
            ->addSelect('tags')
            ->addSelect('categories')
            ->where('p.published = 1')
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

    public function getAdminPosts(PostSearch $search)
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 'tags')
            ->leftJoin('p.categories', 'categories')
            ->addSelect('tags')
            ->addSelect('categories')
            ->orderBy('p.createdAt', 'desc');

        if ($search->getTitle())
            $qb->andWhere('p.title LIKE :title')->setParameter('title', '%'.$search->getTitle().'%');

        if ($search->getCategory())
            $qb->andWhere($qb->expr()->in('p.categories', [$search->getCategory()]));

        if ($search->getTag())
            $qb->andWhere($qb->expr()->in('p.tags', [$search->getTag()]));

        if ($search->isPublished())
            $qb->andWhere('p.published = 1');

        return $qb;
    }

    public function getRecentPosts()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.published = 1')
            ->orderBy('p.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function getPostByCategory(Category $category): ?QueryBuilder
    {
        return $this->createQueryBuilder('p')
                    ->leftJoin('p.tags', 'tags')
                    ->leftJoin('p.categories', 'categories')
                    ->addSelect('tags')
                    ->addSelect('categories')
                    ->where('p.published = 1')
                    ->andWhere('categories.slug = :slug')
                    ->setParameter('slug', $category->getSlug())
                    ->orderBy('p.publishedAt', 'desc');
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
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
    public function findOneBySomeField($value): ?Post
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
