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
    
    const ESTADO_PENDIENTE = 'PENDIENTE';
    const ESTADO_PROCESADO = 'PROCESADO';
    const ESTADO_ERROR = 'ERROR';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var Requisito | null
     * @ORM\ManyToOne(targetEntity="Requisito")
     */
    private $requisito;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $apellido;

    /**
     * @var string|null
     * @ORM\Column(type="string",length=8, nullable=true)
     */
    private $dni;

    /**
     * @var string|null
     * @ORM\Column(type="string",length=11, nullable=true)
     */
    private $cuit;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $regimenIva;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $categoriaIva;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cbu;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tipoCuenta;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $monto;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $rubro;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroCuentaBancaria;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100)
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nodo;

    /**
     * @ORM\ManyToOne(targetEntity=PuntaCajaPago::class, inversedBy="excelIngresos")
     */
    private $puntaCajaPago;
    
    public function __construct()
    {
        $this->setEstado(self::ESTADO_PENDIENTE);
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return Requisito|null
     */
    public function getRequisito(): ?Requisito
    {
        return $this->requisito;
    }
    
    /**
     * @param Requisito|null $requisito
     */
    public function setRequisito(?Requisito $requisito): void
    {
        $this->requisito = $requisito;
    }
    
    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    
    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }
    
    /**
     * @return string|null
     */
    public function getApellido(): ?string
    {
        return $this->apellido;
    }
    
    /**
     * @param string|null $apellido
     */
    public function setApellido(?string $apellido): void
    {
        $this->apellido = $apellido;
    }
    
    /**
     * @return string|null
     */
    public function getDni(): ?string
    {
        return $this->dni;
    }
    
    /**
     * @param string|null $dni
     */
    public function setDni(?string $dni): void
    {
        $this->dni = $dni;
    }
    
    /**
     * @return string|null
     */
    public function getCuit(): ?string
    {
        return $this->cuit;
    }
    
    /**
     * @param string|null $cuit
     */
    public function setCuit(?string $cuit): void
    {
        $this->cuit = $cuit;
    }
    
    /**
     * @return string|null
     */
    public function getRegimenIva(): ?string
    {
        return $this->regimenIva;
    }
    
    /**
     * @param string|null $regimenIva
     */
    public function setRegimenIva(?string $regimenIva): void
    {
        $this->regimenIva = $regimenIva;
    }
    
    /**
     * @return string|null
     */
    public function getCategoriaIva(): ?string
    {
        return $this->categoriaIva;
    }
    
    /**
     * @param string|null $categoriaIva
     */
    public function setCategoriaIva(?string $categoriaIva): void
    {
        $this->categoriaIva = $categoriaIva;
    }
    
    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return string|null
     */
    public function getCbu(): ?string
    {
        return $this->cbu;
    }
    
    /**
     * @param string|null $cbu
     */
    public function setCbu(?string $cbu): void
    {
        $this->cbu = $cbu;
    }
    
    /**
     * @return string|null
     */
    public function getTipoCuenta(): ?string
    {
        return $this->tipoCuenta;
    }
    
    /**
     * @param string|null $tipoCuenta
     */
    public function setTipoCuenta(?string $tipoCuenta): void
    {
        $this->tipoCuenta = $tipoCuenta;
    }
    
    /**
     * @return string|null
     */
    public function getBanco(): ?string
    {
        return $this->banco;
    }
    
    /**
     * @param string|null $banco
     */
    public function setBanco(?string $banco): void
    {
        $this->banco = $banco;
    }
    
    /**
     * @return mixed
     */
    public function getMonto()
    {
        return $this->monto;
    }
    
    /**
     * @param mixed $monto
     */
    public function setMonto($monto): void
    {
        $this->monto = $monto;
    }
    
    /**
     * @return string|null
     */
    public function getRubro(): ?string
    {
        return $this->rubro;
    }
    
    /**
     * @param string|null $rubro
     */
    public function setRubro(?string $rubro): void
    {
        $this->rubro = $rubro;
    }
    
    /**
     * @return string|null
     */
    public function getNumeroCuentaBancaria(): ?string
    {
        return $this->numeroCuentaBancaria;
    }
    
    /**
     * @param string|null $numeroCuentaBancaria
     */
    public function setNumeroCuentaBancaria(?string $numeroCuentaBancaria): void
    {
        $this->numeroCuentaBancaria = $numeroCuentaBancaria;
    }
    
    /**
     * @return string|null
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }
    
    /**
     * @param string|null $estado
     */
    public function setEstado(?string $estado): void
    {
        $this->estado = $estado;
    }

   

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->getApellido().' '.$this->getNombre();
    }

    public function getLocalidad(): ?string
    {
        return $this->localidad;
    }

    public function setLocalidad(?string $localidad): self
    {
        $this->localidad = $localidad;

        return $this;
    }

    public function getNodo(): ?string
    {
        return $this->nodo;
    }

    public function setNodo(?string $nodo): self
    {
        $this->nodo = $nodo;

        return $this;
    }

    public function getPuntaCajaPago(): ?PuntaCajaPago
    {
        return $this->puntaCajaPago;
    }

    public function setPuntaCajaPago(?PuntaCajaPago $puntaCajaPago): self
    {
        $this->puntaCajaPago = $puntaCajaPago;

        return $this;
    }

    
}
