<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehiculeRepository")
 */
class Vehicule
{
    /**
	 * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\CompteRendu", mappedBy="vehicule")
	 */
	private $compteRenduVehicule;

    public function __construct()
    {
        $this->compteRenduVehicule = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }


    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|CompteRendu[]
     */
    public function getCompteRenduVehicule(): Collection
    {
        return $this->compteRenduVehicule;
    }

    public function addCompteRenduVehicule(CompteRendu $compteRenduVehicule): self
    {
        if (!$this->compteRenduVehicule->contains($compteRenduVehicule)) {
            $this->compteRenduVehicule[] = $compteRenduVehicule;
            $compteRenduVehicule->setPraticien($this);
        }

        return $this;
    }

    public function removeCompteRenduVehicule(CompteRendu $compteRenduVehicule): self
    {
        if ($this->compteRenduVehicule->contains($compteRenduVehicule)) {
            $this->compteRenduVehicule->removeElement($compteRenduVehicule);
            // set the owning side to null (unless already changed)
            if ($compteRenduVehicule->getPraticien() === $this) {
                $compteRenduVehicule->setPraticien(null);
            }
        }

        return $this;
    }
}
