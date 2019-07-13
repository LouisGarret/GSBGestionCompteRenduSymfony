<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SecteurRepository")
 */
class Secteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Region", mappedBy="secteur")
     */
    private $region;

    public function __construct()
    {
        $this->region = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegion(): Collection
    {
        return $this->region;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->region->contains($region)) {
            $this->region[] = $region;
            $region->setSecteur($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->region->contains($region)) {
            $this->region->removeElement($region);
            // set the owning side to null (unless already changed)
            if ($region->getSecteur() === $this) {
                $region->setSecteur(null);
            }
        }

        return $this;
    }



}
