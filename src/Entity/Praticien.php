<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PraticienRepository")
 */
class Praticien
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
	private $nom;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $prenom;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $email;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Specialite", inversedBy="praticien")
	 */
	private $specialite;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\TypePraticien", inversedBy="praticien")
	 */
	private $typePraticien;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="praticien")
	 */
	private $region;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\CompteRendu", mappedBy="praticien")
	 */
	private $praticienCompteRendus;

    public function __construct()
    {
        $this->praticienCompteRendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

	public function getNomPrenom(): ?string
	{
		return $this->prenom." ".$this->nom;
	}

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getTypePraticien(): ?TypePraticien
    {
        return $this->typePraticien;
    }

    public function setTypePraticien(?TypePraticien $typePraticien): self
    {
        $this->typePraticien = $typePraticien;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|CompteRendu[]
     */
    public function getPraticienCompteRendus(): Collection
    {
        return $this->praticienCompteRendus;
    }

    public function addPraticienCompteRendus(CompteRendu $praticienCompteRendus): self
    {
        if (!$this->praticienCompteRendus->contains($praticienCompteRendus)) {
            $this->praticienCompteRendus[] = $praticienCompteRendus;
            $praticienCompteRendus->setPraticien($this);
        }

        return $this;
    }

    public function removePraticienCompteRendus(CompteRendu $praticienCompteRendus): self
    {
        if ($this->praticienCompteRendus->contains($praticienCompteRendus)) {
            $this->praticienCompteRendus->removeElement($praticienCompteRendus);
            // set the owning side to null (unless already changed)
            if ($praticienCompteRendus->getPraticien() === $this) {
                $praticienCompteRendus->setPraticien(null);
            }
        }

        return $this;
    }


}