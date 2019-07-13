<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedicamentRepository")
 */
class Medicament
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
    private $DepotLegal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NomCommercial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Famille", inversedBy="medicaments")
     */
    private $famille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Composition;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Effets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ContreIndic;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $PrixEchantillon;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CompteRendu", mappedBy="produits" )
     * @ORM\JoinTable(name="compte_rendu_produit")
     */
    private $compteRenduProduits;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CompteRendu", mappedBy="echantillons" )
     * @ORM\JoinTable(name="compte_rendu_echantillon")
     */
    private $compteRenduEchantillons;

    public function __toString()
    {
        return (string) $this->getNomCommercial();
    }

    public function __construct()
    {
        $this->compteRenduProduits = new ArrayCollection();
        $this->compteRenduEchantillons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepotLegal(): ?string
    {
        return $this->DepotLegal;
    }

    public function setDepotLegal(string $DepotLegal): self
    {
        $this->DepotLegal = $DepotLegal;

        return $this;
    }

    public function getNomCommercial(): ?string
    {
        return $this->NomCommercial;
    }

    public function setNomCommercial(string $NomCommercial): self
    {
        $this->NomCommercial = $NomCommercial;

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->Composition;
    }

    public function setComposition(string $Composition): self
    {
        $this->Composition = $Composition;

        return $this;
    }

    public function getEffets(): ?string
    {
        return $this->Effets;
    }

    public function setEffets(string $Effets): self
    {
        $this->Effets = $Effets;

        return $this;
    }

    public function getContreIndic(): ?string
    {
        return $this->ContreIndic;
    }

    public function setContreIndic(string $ContreIndic): self
    {
        $this->ContreIndic = $ContreIndic;

        return $this;
    }

    public function getPrixEchantillon(): ?float
    {
        return $this->PrixEchantillon;
    }

    public function setPrixEchantillon(?float $PrixEchantillon): self
    {
        $this->PrixEchantillon = $PrixEchantillon;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * @return Collection|CompteRendu[]
     */
    public function getCompteRenduProduits(): Collection
    {
        return $this->compteRenduProduits;
    }

    public function addCompteRenduProduit(CompteRendu $compteRenduProduit): self
    {
        if (!$this->compteRenduProduits->contains($compteRenduProduit)) {
            $this->compteRenduProduits[] = $compteRenduProduit;
            $compteRenduProduit->addProduit($this);
        }

        return $this;
    }

    public function removeCompteRenduProduit(CompteRendu $compteRenduProduit): self
    {
        if ($this->compteRenduProduits->contains($compteRenduProduit)) {
            $this->compteRenduProduits->removeElement($compteRenduProduit);
            $compteRenduProduit->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|CompteRendu[]
     */
    public function getCompteRenduEchantillons(): Collection
    {
        return $this->compteRenduEchantillons;
    }

    public function addCompteRenduEchantillon(CompteRendu $compteRenduEchantillon): self
    {
        if (!$this->compteRenduEchantillons->contains($compteRenduEchantillon)) {
            $this->compteRenduEchantillons[] = $compteRenduEchantillon;
            $compteRenduEchantillon->addEchantillon($this);
        }

        return $this;
    }

    public function removeCompteRenduEchantillon(CompteRendu $compteRenduEchantillon): self
    {
        if ($this->compteRenduEchantillons->contains($compteRenduEchantillon)) {
            $this->compteRenduEchantillons->removeElement($compteRenduEchantillon);
            $compteRenduEchantillon->removeEchantillon($this);
        }

        return $this;
    }

}
