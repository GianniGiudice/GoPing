<?php

namespace App\Repository;

use App\Entity\PublicationReaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationReaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationReaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationReaction[]    findAll()
 * @method PublicationReaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationReaction::class);
    }

    // /**
    //  * @return PublicationReaction[] Returns an array of PublicationReaction objects
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
    public function findOneBySomeField($value): ?PublicationReaction
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
