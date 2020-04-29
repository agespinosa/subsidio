<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActaRepository")
 */
class Acta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sistematica;

    /**
     * @ORM\Column(type="integer")
     */
    private $bovinosVacunadosContraFiebreAftosaVacas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vacunaAntiAftosaMarca;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $vacunaAntiAftosaVencimiento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSistematica(): ?bool
    {
        return $this->sistematica;
    }

    public function setSistematica(bool $sistematica): self
    {
        $this->sistematica = $sistematica;

        return $this;
    }

    public function getBovinosVacunadosContraFiebreAftosaVacas(): ?int
    {
        return $this->bovinosVacunadosContraFiebreAftosaVacas;
    }

    public function setBovinosVacunadosContraFiebreAftosaVacas(int $bovinosVacunadosContraFiebreAftosaVacas): self
    {
        $this->bovinosVacunadosContraFiebreAftosaVacas = $bovinosVacunadosContraFiebreAftosaVacas;

        return $this;
    }

    public function getVacunaAntiAftosaMarca(): ?string
    {
        return $this->vacunaAntiAftosaMarca;
    }

    public function setVacunaAntiAftosaMarca(?string $vacunaAntiAftosaMarca): self
    {
        $this->vacunaAntiAftosaMarca = $vacunaAntiAftosaMarca;

        return $this;
    }

    public function getVacunaAntiAftosaVencimiento(): ?\DateTimeInterface
    {
        return $this->vacunaAntiAftosaVencimiento;
    }

    public function setVacunaAntiAftosaVencimiento(?\DateTimeInterface $vacunaAntiAftosaVencimiento): self
    {
        $this->vacunaAntiAftosaVencimiento = $vacunaAntiAftosaVencimiento;

        return $this;
    }
}
