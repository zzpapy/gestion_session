<?php

namespace App\Repository;

use App\Entity\SessionMat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SessionMat|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionMat|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionMat[]    findAll()
 * @method SessionMat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionMatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionMat::class);
    }

    // /**
    //  * @return SessionMat[] Returns an array of SessionMat objects
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
    public function findOneBySomeField($value): ?SessionMat
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
