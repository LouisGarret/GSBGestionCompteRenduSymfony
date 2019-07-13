<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialiteRepository")
 */
class Specialite
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
     * @ORM\OneToMany(targetEntity="App\Entity\Praticien", mappedBy="specialite")
     */
    private $praticien;

    public function __toString()
    {
        return (string) $this->getLibelle();
    }

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->praticien = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setSpecialite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSpecialite() === $this) {
                $user->setSpecialite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Praticien[]
     */
    public function getPraticien(): Collection
    {
        return $this->praticien;
    }

    public function addPraticien(Praticien $praticien): self
    {
        if (!$this->praticien->contains($praticien)) {
            $this->praticien[] = $praticien;
            $praticien->setSpecialite($this);
        }

        return $this;
    }

    public function removePraticien(Praticien $praticien): self
    {
        if ($this->praticien->contains($praticien)) {
            $this->praticien->removeElement($praticien);
            // set the owning side to null (unless already changed)
            if ($praticien->getSpecialite() === $this) {
                $praticien->setSpecialite(null);
            }
        }

        return $this;
    }


}
