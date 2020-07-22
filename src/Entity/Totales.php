<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TotalesRepository")
 */
class Totales
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $registroId;

    /**
     * @ORM\Column(type="decimal", precision=23, scale=2)
     */
    private $totalAPagar;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalRegistros;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistroId(): ?string
    {
        return $this->registroId;
    }

    public function setRegistroId(?string $registroId): self
    {
        $this->registroId = $registroId;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getTotalAPagar()
    {
        return $this->totalAPagar;
    }
    
    /**
     * @param mixed $totalAPagar
     */
    public function setTotalAPagar($totalAPagar): void
    {
        $this->totalAPagar = $totalAPagar;
    }

   

    public function getTotalRegistros(): ?int
    {
        return $this->totalRegistros;
    }

    public function setTotalRegistros(int $totalRegistros): self
    {
        $this->totalRegistros = $totalRegistros;

        return $this;
    }
}
