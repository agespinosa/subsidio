<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * AtributoConfiguracion
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class AtributoConfiguracion
{
    
    use TimestampableEntity;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

       
    /**
     * @var string|null
     *
     * @ORM\Column(name="clave", type="string", nullable=false)
     * @Assert\NotNull()
     */
    private $clave;

    /**
     * @var string|null
     *     
     * @ORM\Column(type="text", length=65535, nullable=false))
     * @Assert\NotNull()
     */
    private $valor;
    

     /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_baja", type="datetime", nullable=true)     
     */
    private $fechaBaja;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string|null
     */
    public function getClave(): ?string
    {
        return $this->clave;
    }
    
    /**
     * @param string|null $clave
     */
    public function setClave(?string $clave): void
    {
        $this->clave = $clave;
    }
    
    /**
     * @return string|null
     */
    public function getValor(): ?string
    {
        return $this->valor;
    }
    
    /**
     * @param string|null $valor
     */
    public function setValor(?string $valor): void
    {
        $this->valor = $valor;
    }
    
    /**
     * @return \DateTime|null
     */
    public function getFechaBaja(): ?\DateTime
    {
        return $this->fechaBaja;
    }
    
    /**
     * @param \DateTime|null $fechaBaja
     */
    public function setFechaBaja(?\DateTime $fechaBaja): void
    {
        $this->fechaBaja = $fechaBaja;
    }
    
   

}
