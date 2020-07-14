<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CabeceraRepository")
 */
class Cabecera
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $registroId;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaCreacionArchivo;

    /**
     * @ORM\Column(type="time")
     */
    private $horaCreacionArchivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroArchivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroCliente;

    /**
     * @ORM\Column(type="string", length=26)
     */
    private $identificacionArchivo;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaHabilProcesamiento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistroId(): ?string
    {
        return $this->registroId;
    }

    public function setRegistroId(string $registroId): self
    {
        $this->registroId = $registroId;

        return $this;
    }

    public function getFechaCreacionArchivo(): ?\DateTimeInterface
    {
        return $this->fechaCreacionArchivo;
    }

    public function setFechaCreacionArchivo(\DateTimeInterface $fechaCreacionArchivo): self
    {
        $this->fechaCreacionArchivo = $fechaCreacionArchivo;

        return $this;
    }

    public function getHoraCreacionArchivo(): ?\DateTimeInterface
    {
        return $this->horaCreacionArchivo;
    }

    public function setHoraCreacionArchivo(\DateTimeInterface $horaCreacionArchivo): self
    {
        $this->horaCreacionArchivo = $horaCreacionArchivo;

        return $this;
    }

    public function getNumeroArchivo(): ?int
    {
        return $this->numeroArchivo;
    }

    public function setNumeroArchivo(int $numeroArchivo): self
    {
        $this->numeroArchivo = $numeroArchivo;

        return $this;
    }

    public function getNumeroCliente(): ?int
    {
        return $this->numeroCliente;
    }

    public function setNumeroCliente(int $numeroCliente): self
    {
        $this->numeroCliente = $numeroCliente;

        return $this;
    }

    public function getIdentificacionArchivo(): ?string
    {
        return $this->identificacionArchivo;
    }

    public function setIdentificacionArchivo(string $identificacionArchivo): self
    {
        $this->identificacionArchivo = $identificacionArchivo;

        return $this;
    }

    public function getFechaHabilProcesamiento(): ?\DateTimeInterface
    {
        return $this->fechaHabilProcesamiento;
    }

    public function setFechaHabilProcesamiento(\DateTimeInterface $fechaHabilProcesamiento): self
    {
        $this->fechaHabilProcesamiento = $fechaHabilProcesamiento;

        return $this;
    }
}
