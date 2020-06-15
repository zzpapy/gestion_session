<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterielRepository::class)
 */
class Materiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\ManyToMany(targetEntity=Salle::class, inversedBy="materiels",cascade={"persist"})
     */
    private $Salle;

    public function __construct()
    {
        $this->Salle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection|Salle[]
     */
    public function getSalle(): Collection
    {
        return $this->Salle;
    }

    public function addSalle(Salle $salle): self
    {
        if (!$this->Salle->contains($salle)) {
            $this->Salle[] = $salle;
        }

        return $this;
    }

    public function removeSalle(Salle $salle): self
    {
        if ($this->Salle->contains($salle)) {
            $this->Salle->removeElement($salle);
        }

        return $this;
    }
    public function __toString()
    {
        return  strval( $this->nom);
    }
}
