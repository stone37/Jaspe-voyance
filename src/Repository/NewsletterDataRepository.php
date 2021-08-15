<?php

namespace App\Repository;

use App\Entity\NewsletterData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsletterData|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterData|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterData[]    findAll()
 * @method NewsletterData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterData::class);
    }


}
