<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use phpDocumentor\Reflection\Types\This;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompteRenduRepository")
 */
class CompteRendu
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
    private $dateVisite;

    /**
     * @ORM\Column(type="date")
     */
    private $dateSaisie;

    /**
     * @ORM\Column(type="float", nullable=false))
     */
    private $coefficient;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bilan;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $cloture;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $doc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="visiteurCompteRendus")
     */
    private $visiteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Praticien", inversedBy="praticienCompteRendus")
     */
    private $praticien;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remplacant;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $motif;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medicament", inversedBy="compteRenduProduits")
     * @OrderBy({"NomCommercial" = "ASC"})
     * @ORM\JoinTable(name="compte_rendu_produit")
     */
    private $produits;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medicament", inversedBy="compteRenduEchantillons")
     * @ORM\JoinTable(name="compte_rendu_echantillon")
     * @OrderBy({"NomCommercial" = "ASC"})
     */
    private $echantillons;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Vehicule", inversedBy="compteRenduVehicule")
	 */
    private $vehicule;

    public function __toString()
    {
        return (string) $this->getId();
    }

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->echantillons = new ArrayCollection();
        $this->dateSaisie = new DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->dateVisite;
    }

    public function setDateVisite(\DateTimeInterface $dateVisite): self
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    public function getDateSaisie(): ?\DateTimeInterface
    {
        return $this->dateSaisie;
    }

    public function setDateSaisie(\DateTimeInterface $dateSaisie): self
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getBilan(): ?string
    {
        return $this->bilan;
    }

    public function setBilan(string $bilan): self
    {
        $this->bilan = $bilan;

        return $this;
    }

    public function getCloture(): ?bool
    {
        return $this->cloture;
    }

    public function setCloture(bool $cloture): self
    {
        $this->cloture = $cloture;

        return $this;
    }

    public function getDoc(): ?bool
    {
        return $this->doc;
    }

    public function setDoc(bool $doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    public function getRemplacant(): ?string
    {
        return $this->remplacant;
    }

    public function setRemplacant(string $remplacant): self
    {
        $this->remplacant = $remplacant;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getVisiteur(): ?User
    {
        return $this->visiteur;
    }

    public function setVisiteur(?User $visiteur): self
    {
        $this->visiteur = $visiteur;

        return $this;
    }

    public function getPraticien(): ?Praticien
    {
        return $this->praticien;
    }

    public function setPraticien(?Praticien $praticien): self
    {
        $this->praticien = $praticien;

        return $this;
    }

    /**
     * @return Collection|Medicament[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Medicament $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Medicament $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
        }

        return $this;
    }

    /**
     * @return Collection|Medicament[]
     */
    public function getEchantillons(): Collection
    {
        return $this->echantillons;
    }

    public function addEchantillon(Medicament $echantillon): self
    {
        if (!$this->echantillons->contains($echantillon)) {
            $this->echantillons[] = $echantillon;
        }

        return $this;
    }

    public function removeEchantillon(Medicament $echantillon): self
    {
        if ($this->echantillons->contains($echantillon)) {
            $this->echantillons->removeElement($echantillon);
        }

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }

}
