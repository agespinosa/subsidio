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
    
    public function persist(SubsidioPagoProveedores $object){
        $em = $this->getEntityManager();
        return $em->persist($object);
    }
    
    public function flush(SubsidioPagoProveedores $object){
        $em = $this->getEntityManager();
        return $em->flush($object);
    }

    /**
     * @return SubsidioPagoProveedores[] Returns an array of SubsidioPagoProveedores objects
    */
   
    public function findAllDistinct()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->distinct('s.requsito')
            ->getQuery()
            ->getResult()
        ;
    }
   

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
