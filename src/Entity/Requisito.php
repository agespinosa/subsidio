<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequisitoRepository")
 */
class Requisito
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaDesde;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaHasta;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tipoFormaPago;

    /**
     * @ORM\Column(type="string")
     */
    private $fileName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fechaDesde;
    }

    public function setFechaDesde(\DateTimeInterface $fechaDesde): self
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fechaHasta;
    }

    public function setFechaHasta(\DateTimeInterface $fechaHasta): self
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    public function getTipoFormaPago(): ?string
    {
        return $this->tipoFormaPago;
    }

    public function setTipoFormaPago(string $tipoFormaPago): self
    {
        $this->tipoFormaPago = $tipoFormaPago;

        return $this;
    }
    
    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;
    }
    
    
}
