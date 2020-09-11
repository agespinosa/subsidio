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
     * @var Requisito| null
     * @ORM\ManyToOne(targetEntity="Requisito")
     */
    private $requsito;
    
    /**
     * @var Totales| null
     * @ORM\ManyToOne(targetEntity="Totales")
     */
    private $totales;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $registroId;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $tipoPago;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $referenciaCliente;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2, nullable=true)
     */
    private $importeAPagar;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $monedaPago;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaEjecucionPago;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     */
    private $numeroProveedor;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $nombreBeneficiario;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
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
     * @ORM\Column(type="string", length=3, nullable=true)
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
     * @ORM\Column(type="string", nullable=true, length=3)
     */
    private $codigoNovedadOrden;
    
    /**
     * @ORM\Column(type="integer", nullable=true, length=35 )
     */
    private $cuentaBancariaBeneficiario;
    

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
        $this->referenciaCliente = str_pad($referenciaCliente, 16, "0", STR_PAD_LEFT);;

        return $this;
    }

    public function getImporteAPagar(): ?string
    {
        return $this->importeAPagar;
    }

    public function setImporteAPagar(string $importeAPagar): self
    {
        setlocale(LC_MONETARY, 'es_AR');
        $this->importeAPagar = money_format('%2n', $importeAPagar);

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
        $this->numeroProveedor = str_pad($numeroProveedor, 18, " ", STR_PAD_RIGHT);

        return $this;
    }

    public function getNombreBeneficiario(): ?string
    {
        return $this->nombreBeneficiario;
    }

    public function setNombreBeneficiario(string $nombreBeneficiario): self
    {
        $this->nombreBeneficiario = substr(
                                        str_pad($this->clearString($nombreBeneficiario), 60, " ", STR_PAD_RIGHT),
                                        0,
                                            60);

        return $this;
    }
    private function clearString($string){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
        return strtoupper(trim($string, ' '));
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(string $cuit): self
    {
        $cuitTmp = str_replace("-","",$cuit);
        $cuitTmp = str_replace(".","",$cuitTmp);
        $this->cuit = $cuitTmp;

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
        $this->cuentaDebito = str_pad($cuentaDebito, 17, "0", STR_PAD_LEFT);

        return $this;
    }

    public function getMotivoPago(): ?string
    {
        return $this->motivoPago;
    }

    public function setMotivoPago(?string $motivoPago): self
    {
        $this->motivoPago = strtoupper(str_pad($motivoPago, 105, " ", STR_PAD_RIGHT));

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

    public function getCodigoNovedadOrden(): ?string
    {
        return $this->codigoNovedadOrden;
    }

    public function setCodigoNovedadOrden(?string $codigoNovedadOrden): self
    {
        $this->codigoNovedadOrden = str_pad($codigoNovedadOrden,3, "0", STR_PAD_LEFT);

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
     * @return Requisito|null
     */
    public function getRequsito(): ?Requisito
    {
        return $this->requsito;
    }
    
    /**
     * @param Requisito|null $requsito
     */
    public function setRequsito(?Requisito $requsito): void
    {
        $this->requsito = $requsito;
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
    
    /**
     * @return mixed
     */
    public function getCuentaBancariaBeneficiario()
    {
        return $this->cuentaBancariaBeneficiario;
    }
    
    /**
     * @param mixed $cuentaBancariaBeneficiario
     */
    public function setCuentaBancariaBeneficiario($cuentaBancariaBeneficiario): void
    {
        $this->cuentaBancariaBeneficiario = $cuentaBancariaBeneficiario;
    }
    
    
    public function getImporteAPagarString(): string
    {
        return str_pad(
            str_replace(".","", $this->getImporteAPagar()),
            15, "0", STR_PAD_LEFT);
    }

    public function getFechaEjecucionPagoStr(): string {
        return $this->getFechaEjecucionPago()->format('yymd');
    }
    
    public function getCuitStr(): ?string
    {
        return str_pad($this->getCuit(),11, "0", STR_PAD_LEFT);
    }
    
    public function getMedioComunicacionStr(): ?string
    {
        return str_pad("E:".$this->medioComunicacion, 150, " ", STR_PAD_RIGHT);
    }
    
    
}
