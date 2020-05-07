<?php

namespace App\Repository;

use App\Entity\Kinship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Kinship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kinship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kinship[]    findAll()
 * @method Kinship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KinshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kinship::class);
    }

    // /**
    //  * @return Kinship[] Returns an array of Kinship objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kinship
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
