<?php

namespace App\Repository;

use App\Entity\Establecimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Establecimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Establecimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Establecimiento[]    findAll()
 * @method Establecimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablecimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establecimiento::class);
    }

    // /**
    //  * @return Establecimiento[] Returns an array of Establecimiento objects
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
    public function findOneBySomeField($value): ?Establecimiento
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param null |string $term
     * @return Establecimiento[]
     */
    public function findAllWithSearch(?string $term){
        $qb= $this->createQueryBuilder('e')
            ->innerJoin('e.propietario', 'p');
        if($term){
            $qb->andWhere('e.nombre LIKE :term OR e.cantidadHectareas LIKE :term OR p.razonSocial LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
