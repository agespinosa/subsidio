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

    /**
     * @return string|null
     */
    public function getFileSubsidioName(): ?string
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
    
}
