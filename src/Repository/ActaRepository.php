<?php

namespace App\Repository;

use App\Entity\Acta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acta[]    findAll()
 * @method Acta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acta::class);
    }

     /**
     * @return Acta[] Returns an array of Acta objects
     */

    public function findAllActasOrderedByFechaVencimiento()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.vacunaAntiAftosaVencimiento IS NOT NULL')
            ->orderBy('a.vacunaAntiAftosaVencimiento', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Acta
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
