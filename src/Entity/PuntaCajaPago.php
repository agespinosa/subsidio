<?php

namespace App\Entity;

use App\Repository\PuntaCajaPagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PuntaCajaPagoRepository::class)
 */
class PuntaCajaPago
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivoPago;

    /**
     * @ORM\Column(type="float")
     */
    private $montoTotal;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codigoBanco;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cantidadRegistros;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tipoSoporte;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileExcelOriginalPath;
    
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileExcelOriginalName;

    /**
     * @ORM\OneToMany(targetEntity=ExcelIngreso::class, mappedBy="puntaCajaPago")
     */
    private $excelIngresos;

    public function __construct()
    {
        $this->excelIngresos = new ArrayCollection();
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

    public function getMotivoPago(): ?string
    {
        return $this->motivoPago;
    }

    public function setMotivoPago(?string $motivoPago): self
    {
        $this->motivoPago = $motivoPago;

        return $this;
    }

    public function getMontoTotal(): ?float
    {
        return $this->montoTotal;
    }

    public function setMontoTotal(float $montoTotal): self
    {
        $this->montoTotal = $montoTotal;

        return $this;
    }

    public function getCodigoBanco(): ?string
    {
        return $this->codigoBanco;
    }

    public function setCodigoBanco(?string $codigoBanco): self
    {
        $this->codigoBanco = $codigoBanco;

        return $this;
    }

    public function getCantidadRegistros(): ?int
    {
        return $this->cantidadRegistros;
    }

    public function setCantidadRegistros(?int $cantidadRegistros): self
    {
        $this->cantidadRegistros = $cantidadRegistros;

        return $this;
    }

    public function getTipoSoporte(): ?string
    {
        return $this->tipoSoporte;
    }

    public function setTipoSoporte(string $tipoSoporte): self
    {
        $this->tipoSoporte = $tipoSoporte;

        return $this;
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

    /**
     * @return Collection|ExcelIngreso[]
     */
    public function getExcelIngresos(): Collection
    {
        return $this->excelIngresos;
    }

    public function addExcelIngreso(ExcelIngreso $excelIngreso): self
    {
        if (!$this->excelIngresos->contains($excelIngreso)) {
            $this->excelIngresos[] = $excelIngreso;
            $excelIngreso->setPuntaCajaPago($this);
        }

        return $this;
    }

    public function removeExcelIngreso(ExcelIngreso $excelIngreso): self
    {
        if ($this->excelIngresos->removeElement($excelIngreso)) {
            // set the owning side to null (unless already changed)
            if ($excelIngreso->getPuntaCajaPago() === $this) {
                $excelIngreso->setPuntaCajaPago(null);
            }
        }

        return $this;
    }

 
}
