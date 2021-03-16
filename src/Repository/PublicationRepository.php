<?php

namespace App\Repository;

use App\Entity\Publication;
use App\Entity\PublicationReaction;
use App\Entity\Reaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function findAll()
    {
        return $this->findBy([], ['publication_date' => 'DESC']);
    }

    // /**
    //  * @return Publication[] Returns an array of Publication objects
    //  */
    /**
     * @param int $max
     * @return int|mixed|string
     */
    public function getLastPublications($max = 10)
    {
        /*
            SELECT p.id, p.author_id, p.content, p.publication_date, COUNT(reaction.id)
            FROM publication AS p
            LEFT JOIN publication_reaction ON publication_reaction.publication_id = p.id
            LEFT JOIN reaction ON publication_reaction.reaction_id = reaction.id
            GROUP BY p.id, reaction_id
         */

        return $this->createQueryBuilder('p')
            ->select('p.id AS publication_id, IDENTITY(p.author) AS author_id, IDENTITY(pr.author) AS author_reaction_id, p.content, p.publication_date, r.id AS reaction_id, COUNT(r) AS nb_reactions')
            ->leftJoin(PublicationReaction::class, 'pr', 'WITH', 'pr.publication = p')
            ->leftJoin(Reaction::class, 'r', 'WITH', 'pr.reaction = r')
            ->orderBy('p.id', 'DESC')
            ->groupBy('p')
            ->addGroupBy('r')
            ->addGroupBy('pr')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Publication
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
