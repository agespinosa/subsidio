<?php

namespace App\Repository;

use App\Entity\RegimenTenencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegimenTenencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegimenTenencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegimenTenencia[]    findAll()
 * @method RegimenTenencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegimenTenenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegimenTenencia::class);
    }

    // /**
    //  * @return RegimenTenencia[] Returns an array of RegimenTenencia objects
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
    public function findOneBySomeField($value): ?RegimenTenencia
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
