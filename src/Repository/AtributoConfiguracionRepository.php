<?php

namespace AppBundle\Repository;

use App\Entity\Cabecera;
use AppBundle\Entity\AtributoConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * AtributoConfiguracionRepository
 *
 */
class AtributoConfiguracionRepository extends ServiceEntityRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AtributoConfiguracion::class);
    }
    
    public function persist(AtributoConfiguracion $object){
        $em = $this->getEntityManager();
        return $em->persist($object);
    }
    
    public function flush(AtributoConfiguracion $object){
        $em = $this->getEntityManager();
        return $em->flush($object);
    }
    
    public function findAtributoConfiguracionByClave($clave){
        return $this->createQueryBuilder('ac')
            ->andWhere('ac.clave = :clave')
            ->andWhere('ac.fechaBaja is null')
            ->setParameter('clave', $clave)
            ->getQuery()
            ->getResult()
            ;
    }
}


?>