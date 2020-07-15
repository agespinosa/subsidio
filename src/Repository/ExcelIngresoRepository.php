<?php

namespace App\Repository;

use App\Entity\ExcelIngreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExcelIngreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExcelIngreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExcelIngreso[]    findAll()
 * @method ExcelIngreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcelIngresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExcelIngreso::class);
    }
    
    public function persist(ExcelIngreso $excelIngreso){
        $em = $this->getEntityManager();
        return $em->persist($excelIngreso);
    }
    
    public function flush(ExcelIngreso $excelIngreso){
        $em = $this->getEntityManager();
        return $em->flush($excelIngreso);
    }

    // /**
    //  * @return ExcelIngreso[] Returns an array of ExcelIngreso objects
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
    public function findOneBySomeField($value): ?ExcelIngreso
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
