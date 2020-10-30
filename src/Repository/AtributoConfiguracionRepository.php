<?php

namespace App\Repository;

use App\Entity\AtributoConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AtributoConfiguracion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AtributoConfiguracion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AtributoConfiguracion[]    findAll()
 * @method AtributoConfiguracion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
    
    public function findAtributoConfiguracionByClave($clave): AtributoConfiguracion{
        return $this->createQueryBuilder('ac')
            ->andWhere('ac.clave = :clave')
            ->andWhere("ac.fechaBaja is null or ac.fechaBaja = '0000-00-00 00:00:00'")
            ->setParameter('clave', $clave)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('atributo_configuracion')
            ->addSelect('atributo_configuracion');
        
        if($term){
            $qb->andWhere('atributo_configuracion.clave LIKE :val OR atributo_configuracion.valor LIKE :val')
                ->setParameter('val', '%'.$term.'%');
        }
        return $qb
            ->orderBy('atributo_configuracion.createdAt', 'DESC');
        
    }
}


?>