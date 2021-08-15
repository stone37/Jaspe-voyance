<?php

namespace App\Repository;

use App\Entity\Event;
use App\Model\Admin\EventSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getEvents(EventSearch $search)
    {
        $qb = $this->createQueryBuilder('e');

        if ($search->getCity())
            $qb->andWhere('e.city LIKE :city')->setParameter('city', '%'.$search->getCity().'%');

        if ($search->isEnabled())
            $qb->andWhere('e.enabled = 1');

        return $qb;
    }

    public function getEventEnabled()
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.enabled = 1')
            ->andWhere('e.eventDateAt >= :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('e.eventDateAt', 'asc');

        return $qb;
    }

    public function getEvent($slug)
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.enabled = 1')
            ->andWhere('e.slug = :slug')
            ->setParameter('slug', $slug);

        try
        {
            $qb = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            sprintf("Exception: %s", $exception->getMessage());
        }

        return $qb;
    }

    public function getNumber()
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->where('e.enabled = 1')
            ->andWhere('e.eventDateAt >= :date')
            ->setParameter('date', new \DateTime());

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getLastEvent()
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.enabled = 1')
            ->andWhere('e.eventDateAt >= :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('e.eventDateAt', 'asc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
