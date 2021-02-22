<?php

namespace App\Repository;

use App\Entity\PuntaCajaPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PuntaCajaPago|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuntaCajaPago|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuntaCajaPago[]    findAll()
 * @method PuntaCajaPago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuntaCajaPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuntaCajaPago::class);
    }
    
    /**
     * @param null|string $term
     */
    public function getWithSearchQueryBuilder(?string $term):QueryBuilder
    {
        $qb= $this->createQueryBuilder('p');
        if($term){
            $qb->andWhere('p.motivoPago LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }
        
        return $qb
            ->orderBy('p.id', 'DESC');
    }
}
