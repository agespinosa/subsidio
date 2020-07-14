<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubsidioPagoProveedoresRepository")
 */
class SubsidioPagoProveedores
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var Cabecera| null
     * @ORM\ManyToOne(targetEntity="Cabecera")
     */
    private $cabecera;
    
    /**
     * @var ExcelIngreso| null
     * @ORM\ManyToOne(targetEntity="ExcelIngreso")
     */
    private $excelIngreso;
    
    /**
     * @var Totales| null
     * @ORM\ManyToOne(targetEntity="Totales")
     */
    private $totales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $registroId;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $tipoPago;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $referenciaCliente;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $importeAPagar;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $monedaPago;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaEjecucionPago;

    /**
     * @ORM\Column(type="string", length=47, nullable=true)
     */
    private $numeroProveedor;

    /**
     * @ORM\Column(type="string", length=65)
     */
    private $nombreBeneficiario;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domicilio;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $localidad;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $codigoPostal;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $medioComunicacion;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $sucursal;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $tipoCuenta;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $monedaCuenta;

    /**
     * @ORM\Column(type="string", length=22, nullable=true)
     */
    private $numeroCuenta;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $formaEntregaCheque;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $sucursalPrestador;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $monedaCuentaDebito;

    /**
     * @ORM\Column(type="string", length=17, nullable=true)
     */
    private $cuentaDebito;

    /**
     * @ORM\Column(type="string", length=105, nullable=true)
     */
    private $motivoPago;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $indicacionesAdicionales;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $requiereReciboImpreso;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $conRecurso;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $numeroInstrumentoPago;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoNovedadOrden;

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

    public function getTipoPago(): ?string
    {
        return $this->tipoPago;
    }

    public function setTipoPago(string $tipoPago): self
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    public function getReferenciaCliente(): ?string
    {
        return $this->referenciaCliente;
    }

    public function setReferenciaCliente(string $referenciaCliente): self
    {
        $this->referenciaCliente = $referenciaCliente;

        return $this;
    }

    public function getImporteAPagar(): ?string
    {
        return $this->importeAPagar;
    }

    public function setImporteAPagar(string $importeAPagar): self
    {
        $this->importeAPagar = $importeAPagar;

        return $this;
    }

    public function getMonedaPago(): ?string
    {
        return $this->monedaPago;
    }

    public function setMonedaPago(string $monedaPago): self
    {
        $this->monedaPago = $monedaPago;

        return $this;
    }

    public function getFechaEjecucionPago(): ?\DateTimeInterface
    {
        return $this->fechaEjecucionPago;
    }

    public function setFechaEjecucionPago(\DateTimeInterface $fechaEjecucionPago): self
    {
        $this->fechaEjecucionPago = $fechaEjecucionPago;

        return $this;
    }

    public function getNumeroProveedor(): ?string
    {
        return $this->numeroProveedor;
    }

    public function setNumeroProveedor(?string $numeroProveedor): self
    {
        $this->numeroProveedor = $numeroProveedor;

        return $this;
    }

    public function getNombreBeneficiario(): ?string
    {
        return $this->nombreBeneficiario;
    }

    public function setNombreBeneficiario(string $nombreBeneficiario): self
    {
        $this->nombreBeneficiario = $nombreBeneficiario;

        return $this;
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(string $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getDomicilio(): ?string
    {
        return $this->domicilio;
    }

    public function setDomicilio(?string $domicilio): self
    {
        $this->domicilio = $domicilio;

        return $this;
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

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function setCodigoPostal(?string $codigoPostal): self
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    public function getMedioComunicacion(): ?string
    {
        return $this->medioComunicacion;
    }

    public function setMedioComunicacion(?string $medioComunicacion): self
    {
        $this->medioComunicacion = $medioComunicacion;

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

    public function getSucursal(): ?string
    {
        return $this->sucursal;
    }

    public function setSucursal(?string $sucursal): self
    {
        $this->sucursal = $sucursal;

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

    public function getMonedaCuenta(): ?string
    {
        return $this->monedaCuenta;
    }

    public function setMonedaCuenta(string $monedaCuenta): self
    {
        $this->monedaCuenta = $monedaCuenta;

        return $this;
    }

    public function getNumeroCuenta(): ?string
    {
        return $this->numeroCuenta;
    }

    public function setNumeroCuenta(?string $numeroCuenta): self
    {
        $this->numeroCuenta = $numeroCuenta;

        return $this;
    }

    public function getFormaEntregaCheque(): ?string
    {
        return $this->formaEntregaCheque;
    }

    public function setFormaEntregaCheque(?string $formaEntregaCheque): self
    {
        $this->formaEntregaCheque = $formaEntregaCheque;

        return $this;
    }

    public function getSucursalPrestador(): ?string
    {
        return $this->sucursalPrestador;
    }

    public function setSucursalPrestador(?string $sucursalPrestador): self
    {
        $this->sucursalPrestador = $sucursalPrestador;

        return $this;
    }

    public function getMonedaCuentaDebito(): ?string
    {
        return $this->monedaCuentaDebito;
    }

    public function setMonedaCuentaDebito(?string $monedaCuentaDebito): self
    {
        $this->monedaCuentaDebito = $monedaCuentaDebito;

        return $this;
    }

    public function getCuentaDebito(): ?string
    {
        return $this->cuentaDebito;
    }

    public function setCuentaDebito(?string $cuentaDebito): self
    {
        $this->cuentaDebito = $cuentaDebito;

        return $this;
    }

    public function getMotivoPago(): ?string
    {
        return $this->motivoPago;
    }

    public function setMotivoPago(?string $motivoPago): self
    {
        $this->motivoPago = $motivoPago;

        return $this;
    }

    public function getIndicacionesAdicionales(): ?string
    {
        return $this->indicacionesAdicionales;
    }

    public function setIndicacionesAdicionales(?string $indicacionesAdicionales): self
    {
        $this->indicacionesAdicionales = $indicacionesAdicionales;

        return $this;
    }

    public function getRequiereReciboImpreso(): ?string
    {
        return $this->requiereReciboImpreso;
    }

    public function setRequiereReciboImpreso(?string $requiereReciboImpreso): self
    {
        $this->requiereReciboImpreso = $requiereReciboImpreso;

        return $this;
    }

    public function getConRecurso(): ?string
    {
        return $this->conRecurso;
    }

    public function setConRecurso(?string $conRecurso): self
    {
        $this->conRecurso = $conRecurso;

        return $this;
    }

    public function getNumeroInstrumentoPago(): ?string
    {
        return $this->numeroInstrumentoPago;
    }

    public function setNumeroInstrumentoPago(?string $numeroInstrumentoPago): self
    {
        $this->numeroInstrumentoPago = $numeroInstrumentoPago;

        return $this;
    }

    public function getCodigoNovedadOrden(): ?int
    {
        return $this->codigoNovedadOrden;
    }

    public function setCodigoNovedadOrden(?int $codigoNovedadOrden): self
    {
        $this->codigoNovedadOrden = $codigoNovedadOrden;

        return $this;
    }
    
    /**
     * @return Cabecera | null
     */
    public function getCabecera(): ?Cabecera
    {
        return $this->cabecera;
    }
    
    /**
     * @param Cabecera| null $cabecera
     */
    public function setCabecera(?Cabecera $cabecera): void
    {
        $this->cabecera = $cabecera;
    }
    
    /**
     * @return ExcelIngreso| null
     */
    public function getExcelIngreso(): ?ExcelIngreso
    {
        return $this->excelIngreso;
    }
    
    /**
     * @param ExcelIngreso| null $excelIngreso
     */
    public function setExcelIngreso(?ExcelIngreso $excelIngreso): void
    {
        $this->excelIngreso = $excelIngreso;
    }
    
    /**
     * @return Totales| null
     */
    public function getTotales(): ?Totales
    {
        return $this->totales;
    }
    
    /**
     * @param Totales| null $totales
     */
    public function setTotales(?Totales $totales): void
    {
        $this->totales = $totales;
    }
    
    
}
