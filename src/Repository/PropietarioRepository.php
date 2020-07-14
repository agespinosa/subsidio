<?php

namespace App\Repository;

use App\Entity\Propietario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Propietario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propietario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propietario[]    findAll()
 * @method Propietario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropietarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propietario::class);
    }

    /**
    * @return Propietario[] Returns an array of Propietario objects
    */
    public function findAllPropietarioOrderedByRazonSocial()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.cuit IS NOT NULL')
            ->orderBy('p.razonSocial', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param null|string $term
     */
   public function getWithSearchQueryBuilder(?string $term):QueryBuilder
   {
        $qb= $this->createQueryBuilder('p');
        if($term){
            $qb->andWhere('p.cuit LIKE :term OR p.razonSocial LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
            ->orderBy('p.createdAt', 'DESC');
   }


    /*
    public function findOneBySomeField($value): ?Propietario
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
