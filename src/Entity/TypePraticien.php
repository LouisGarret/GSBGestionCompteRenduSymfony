<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypePraticienRepository")
 */
class TypePraticien
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
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Praticien", mappedBy="typePraticien")
     */
    private $praticien;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->praticien = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getLibelle();
    }

	/**
	 * @return mixed
	 */
	public function getId()
                        	{
                        		return $this->id;
                        	}

	/**
	 * @param mixed $id
	 */
	public function setId($id): void
                        	{
                        		$this->id = $id;
                        	}

	/**
	 * @return mixed
	 */
	public function getCode()
                        	{
                        		return $this->code;
                        	}

	/**
	 * @param mixed $code
	 */
	public function setCode($code): void
                        	{
                        		$this->code = $code;
                        	}

	/**
	 * @return mixed
	 */
	public function getLibelle()
                        	{
                        		return $this->libelle;
                        	}

	/**
	 * @param mixed $libelle
	 */
	public function setLibelle($libelle): void
                        	{
                        		$this->libelle = $libelle;
                        	}

	/**
	 * @return mixed
	 */
	public function getLieu()
                        	{
                        		return $this->lieu;
                        	}

	/**
	 * @param mixed $lieu
	 */
	public function setLieu($lieu): void
                        	{
                        		$this->lieu = $lieu;
                        	}

	/**
	 * @return mixed
	 */
	public function getUser()
                        	{
                        		return $this->user;
                        	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user): void
                        	{
                        		$this->user = $user;
                        	}

    public function addUser(Praticien $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setTypePraticien($this);
        }

        return $this;
    }

    public function removeUser(Praticien $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getTypePraticien() === $this) {
                $user->setTypePraticien(null);
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
            $praticien->setTypePraticien($this);
        }

        return $this;
    }

    public function removePraticien(Praticien $praticien): self
    {
        if ($this->praticien->contains($praticien)) {
            $this->praticien->removeElement($praticien);
            // set the owning side to null (unless already changed)
            if ($praticien->getTypePraticien() === $this) {
                $praticien->setTypePraticien(null);
            }
        }

        return $this;
    }


}
