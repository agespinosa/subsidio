<?php

namespace App\Repository;

use App\Entity\SubsidioPagoProveedores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubsidioPagoProveedores|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubsidioPagoProveedores|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubsidioPagoProveedores[]    findAll()
 * @method SubsidioPagoProveedores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubsidioPagoProveedoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubsidioPagoProveedores::class);
    }

    // /**
    //  * @return SubsidioPagoProveedores[] Returns an array of SubsidioPagoProveedores objects
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
    public function findOneBySomeField($value): ?SubsidioPagoProveedores
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
