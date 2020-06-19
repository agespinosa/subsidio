<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstablecimientoRepository")
 */
class Establecimiento
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidadHectareas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Propietario", inversedBy="establecimientos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propietario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCantidadHectareas(): ?int
    {
        return $this->cantidadHectareas;
    }

    public function setCantidadHectareas(int $cantidadHectareas): self
    {
        $this->cantidadHectareas = $cantidadHectareas;

        return $this;
    }

    public function getPropietario(): ?Propietario
    {
        return $this->propietario;
    }

    public function setPropietario(?Propietario $propietario): self
    {
        $this->propietario = $propietario;

        return $this;
    }
}
