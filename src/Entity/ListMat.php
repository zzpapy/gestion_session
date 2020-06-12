<?php

namespace App\Entity;

use App\Repository\ListMatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListMatRepository::class)
 */
class ListMat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $nomMat;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr;

    /**
     * @ORM\ManyToMany(targetEntity=SessionMat::class, mappedBy="listMat")
     */
    private $sessionMats;

    public function __construct()
    {
        $this->sessionMats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMat(): ?int
    {
        return $this->nomMat;
    }

    public function setNomMat(?int $nomMat): self
    {
        $this->nomMat = $nomMat;

        return $this;
    }

    public function getNbr(): ?int
    {
        return $this->nbr;
    }

    public function setNbr(int $nbr): self
    {
        $this->nbr = $nbr;

        return $this;
    }

    /**
     * @return Collection|SessionMat[]
     */
    public function getSessionMats(): Collection
    {
        return $this->sessionMats;
    }

    public function addSessionMat(SessionMat $sessionMat): self
    {
        if (!$this->sessionMats->contains($sessionMat)) {
            $this->sessionMats[] = $sessionMat;
            $sessionMat->addListMat($this);
        }

        return $this;
    }

    public function removeSessionMat(SessionMat $sessionMat): self
    {
        if ($this->sessionMats->contains($sessionMat)) {
            $this->sessionMats->removeElement($sessionMat);
            $sessionMat->removeListMat($this);
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->nomMat;
    }
}
