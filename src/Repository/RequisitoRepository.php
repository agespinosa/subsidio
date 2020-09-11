<?php

namespace App\Repository;

use App\Entity\Requisito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Requisito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requisito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requisito[]    findAll()
 * @method Requisito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequisitoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Requisito::class);
    }

    // /**
    //  * @return Requisito[] Returns an array of Requisito objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Requisito
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
