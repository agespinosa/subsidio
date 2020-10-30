<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequisitoRepository")
 */
class Requisito
{
    
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
     * @ORM\Column(type="date")
     */
    private $fechaDesde;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tipoFormaPago;

    /**
     * @ORM\Column(type="string")
     */
    private $fileExcelOriginalPath;
    
    
    /**
     * @ORM\Column(type="string")
     */
    private $fileExcelOriginalName;

    /**
     * @var string |null
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileSubsidioPath;
    
    /**
     * @var string |null
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileSubsidioName;
    
    
    /**
     * @var string |null
     * @ORM\Column(type="string", nullable=true, length=105)
     */
    private $motivoPago;
    
    
    /**
     * @var string |null
     * @ORM\Column(type="string", nullable=true, length=17)
     */
    private $cuantaOrigenFodos;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalBeneficiarios;
    
    /**
     * @ORM\Column(type="decimal", precision=23, scale=2, nullable=true)
     */
    private $totalMontoPesos;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroArchivoPago;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroReferenciaClienteFila;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $estado;
    
    public function __construct()
    {
        $this->setEstado(self::ESTADO_PENDIENTE);
    }
    
    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }
    
    /**
     * @param mixed $estado
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }
    
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
    
    /**
     * @return string|null
     */
    public function getFileSubsidioName(): ?string
    {
        return $this->fileSubsidioName;
    }
    
    public function getFileSubsidioNameWithOutExtension(): ?string
    {
        return $this->fileSubsidioName;
    }

    /**
     * @param string|null $fileSubsidioName
     */
    public function setFileSubsidioName(?string $fileSubsidioName): void
    {
        $this->fileSubsidioName = $fileSubsidioName;
    }
    
    /**
     * @return string|null
     */
    public function getMotivoPago(): ?string
    {
        return $this->motivoPago;
    }
    
    /**
     * @param string|null $motivoPago
     */
    public function setMotivoPago(?string $motivoPago): void
    {
        $this->motivoPago = $motivoPago;
    }
    
    /**
     * @return string|null
     */
    public function getCuantaOrigenFodos(): ?string
    {
        return $this->cuantaOrigenFodos;
    }
    
    /**
     * @param string|null $cuantaOrigenFodos
     */
    public function setCuantaOrigenFodos(?string $cuantaOrigenFodos): void
    {
        $this->cuantaOrigenFodos = $cuantaOrigenFodos;
    }
    
    public function getMotivoPagoStr(): ?string
    {
        return $this->motivoPago;
    }
    
    /**
     * @return mixed
     */
    public function getTotalBeneficiarios()
    {
        return $this->totalBeneficiarios;
    }
    
    /**
     * @param mixed $totalBeneficiarios
     */
    public function setTotalBeneficiarios($totalBeneficiarios): void
    {
        $this->totalBeneficiarios = $totalBeneficiarios;
    }
    
    /**
     * @return mixed
     */
    public function getTotalMontoPesos()
    {
        return $this->totalMontoPesos;
    }
    
    /**
     * @param mixed $totalMontoPesos
     */
    public function setTotalMontoPesos($totalMontoPesos): void
    {
        $this->totalMontoPesos = $totalMontoPesos;
    }
    
    /**
     * @return mixed
     */
    public function getNumeroArchivoPago()
    {
        return $this->numeroArchivoPago;
    }
    
    /**
     * @param mixed $numeroArchivoPago
     */
    public function setNumeroArchivoPago($numeroArchivoPago): void
    {
        $this->numeroArchivoPago = $numeroArchivoPago;
    }
    
    /**
     * @return mixed
     */
    public function getNumeroReferenciaClienteFila()
    {
        return $this->numeroReferenciaClienteFila;
    }
    
    /**
     * @param mixed $numeroReferenciaClienteFila
     */
    public function setNumeroReferenciaClienteFila($numeroReferenciaClienteFila): void
    {
        $this->numeroReferenciaClienteFila = $numeroReferenciaClienteFila;
    }
    
    /**
     * @return mixed
     */
    public function getFileExcelOriginalPath()
    {
        return $this->fileExcelOriginalPath;
    }
    
    /**
     * @param mixed $fileExcelOriginalPath
     */
    public function setFileExcelOriginalPath($fileExcelOriginalPath): void
    {
        $this->fileExcelOriginalPath = $fileExcelOriginalPath;
    }
    
    /**
     * @return string|null
     */
    public function getFileSubsidioPath(): ?string
    {
        return $this->fileSubsidioPath;
    }
    
    /**
     * @param string|null $fileSubsidioPath
     */
    public function setFileSubsidioPath(?string $fileSubsidioPath): void
    {
        $this->fileSubsidioPath = $fileSubsidioPath;
    }
    
    /**
     * @return mixed
     */
    public function getFileExcelOriginalName()
    {
        return $this->fileExcelOriginalName;
    }
    
    /**
     * @param mixed $fileExcelOriginalName
     */
    public function setFileExcelOriginalName($fileExcelOriginalName): void
    {
        $this->fileExcelOriginalName = $fileExcelOriginalName;
    }
    
}
