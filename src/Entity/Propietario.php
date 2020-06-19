<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropietarioRepository")
 */
class Propietario
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $renspa;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $razonSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domicilio;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codigoPostal;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Gedmo\Slug(fields={"cuit"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Establecimiento", mappedBy="propietario")
     */
    private $establecimientos;

    public function __construct()
    {
        $this->establecimientos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRenspa(): ?string
    {
        return $this->renspa;
    }

    public function setRenspa(string $renspa): self
    {
        $this->renspa = $renspa;

        return $this;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function setRazonSocial(string $razonSocial): self
    {
        $this->razonSocial = $razonSocial;

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

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function setCodigoPostal(string $codigoPostal): self
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * @param mixed $cuit
     */
    public function setCuit($cuit): void
    {
        $this->cuit = $cuit;
    }

    /**
     * @return Collection|Establecimiento[]
     */
    public function getEstablecimientos(): Collection
    {
        return $this->establecimientos;
    }

    public function addEstablecimiento(Establecimiento $establecimiento): self
    {
        if (!$this->establecimientos->contains($establecimiento)) {
            $this->establecimientos[] = $establecimiento;
            $establecimiento->setPropietario($this);
        }

        return $this;
    }

    public function removeEstablecimiento(Establecimiento $establecimiento): self
    {
        if ($this->establecimientos->contains($establecimiento)) {
            $this->establecimientos->removeElement($establecimiento);
            // set the owning side to null (unless already changed)
            if ($establecimiento->getPropietario() === $this) {
                $establecimiento->setPropietario(null);
            }
        }

        return $this;
    }


}
