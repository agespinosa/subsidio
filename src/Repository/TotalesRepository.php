<?php

namespace App\Repository;

use App\Entity\Totales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Totales|null find($id, $lockMode = null, $lockVersion = null)
 * @method Totales|null findOneBy(array $criteria, array $orderBy = null)
 * @method Totales[]    findAll()
 * @method Totales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TotalesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Totales::class);
    }
    
    public function persist(Totales $object){
        $em = $this->getEntityManager();
        return $em->persist($object);
    }
    
    public function flush(Totales $object){
        $em = $this->getEntityManager();
        return $em->flush($object);
    }

    // /**
    //  * @return Totales[] Returns an array of Totales objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Totales
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
