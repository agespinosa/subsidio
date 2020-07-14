<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExcelIngresoRepository")
 */
class ExcelIngreso
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $apellido;

    /**
     * @ORM\Column(type="integer")
     */
    private $dni;

    /**
     * @ORM\Column(type="integer")
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $regimenIva;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $categoriaIva;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cbu;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tipoCuenta;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $monto;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $rubro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroCuentaBancaria;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $estado;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDni(): ?int
    {
        return $this->dni;
    }

    public function setDni(int $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getCuit(): ?int
    {
        return $this->cuit;
    }

    public function setCuit(int $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getRegimenIva(): ?string
    {
        return $this->regimenIva;
    }

    public function setRegimenIva(?string $regimenIva): self
    {
        $this->regimenIva = $regimenIva;

        return $this;
    }

    public function getCategoriaIva(): ?string
    {
        return $this->categoriaIva;
    }

    public function setCategoriaIva(?string $categoriaIva): self
    {
        $this->categoriaIva = $categoriaIva;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCbu(): ?string
    {
        return $this->cbu;
    }

    public function setCbu(?string $cbu): self
    {
        $this->cbu = $cbu;

        return $this;
    }

    public function getTipoCuenta(): ?string
    {
        return $this->tipoCuenta;
    }

    public function setTipoCuenta(?string $tipoCuenta): self
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function setBanco(?string $banco): self
    {
        $this->banco = $banco;

        return $this;
    }

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(string $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getRubro(): ?string
    {
        return $this->rubro;
    }

    public function setRubro(?string $rubro): self
    {
        $this->rubro = $rubro;

        return $this;
    }

    public function getNumeroCuentaBancaria(): ?string
    {
        return $this->numeroCuentaBancaria;
    }

    public function setNumeroCuentaBancaria(?string $numeroCuentaBancaria): self
    {
        $this->numeroCuentaBancaria = $numeroCuentaBancaria;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
