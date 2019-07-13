<?php
// src/Entity/UserFixtures.phpures.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    const PRATICIEN = "ROLE_PRATICIEN";
    const VISITEUR = "ROLE_VISITEUR";
    const RESPONSABLE = "ROLE_RESP_SECTEUR";
    const DELEGUE = "ROLE_DELEG_REGIONAL";
    const ADMIN = "ROLE_ADMIN";
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="user")
     */
    private $region;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompteRendu", mappedBy="visiteur")
     */
    private $visiteurCompteRendus;


    public function __toString()
    {
        return (string) $this->getUsername();
    }

    public function __construct()
    {
        parent::__construct();
        $this->praticienCompteRendus = new ArrayCollection();
        $this->visiteurCompteRendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    /**
     * @return Collection|CompteRendu[]
     */
    public function getVisiteurCompteRendus(): Collection
    {
        return $this->visiteurCompteRendus;
    }

    public function addVisiteurCompteRendus(CompteRendu $visiteurCompteRendus): self
    {
        if (!$this->visiteurCompteRendus->contains($visiteurCompteRendus)) {
            $this->visiteurCompteRendus[] = $visiteurCompteRendus;
            $visiteurCompteRendus->setVisiteur($this);
        }

        return $this;
    }

    public function removeVisiteurCompteRendus(CompteRendu $visiteurCompteRendus): self
    {
        if ($this->visiteurCompteRendus->contains($visiteurCompteRendus)) {
            $this->visiteurCompteRendus->removeElement($visiteurCompteRendus);
            // set the owning side to null (unless already changed)
            if ($visiteurCompteRendus->getVisiteur() === $this) {
                $visiteurCompteRendus->setVisiteur(null);
            }
        }

        return $this;
    }




}