<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float")
     */
    private $nb_places;

    /**
     * @ORM\ManyToMany(targetEntity=Stagiaire::class, mappedBy="sessions")
     */
    private $stagiaires;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="session")
     */
    private $programmes;

    /**
     * @ORM\OneToMany(targetEntity=Vacances::class, mappedBy="session")
     */
    private $vacances;

   

    public function __construct()
    {
        $this->stagiaires = new ArrayCollection();
        $this->programmes = new ArrayCollection();
        $this->vacances = new ArrayCollection();
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getNbPlaces(): ?float
    {
        return $this->nb_places;
    }

    public function setNbPlaces(float $nb_places): self
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    /**
     * @return Collection|Stagiaire[]
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires[] = $stagiaire;
            $stagiaire->addSession($this);
        }

        return $this;
    }

    public function full()
    {
        if (count($this->stagiaires) >= $this->nb_places) {
           return false;
        }

        return $this->nb_places - count($this->stagiaires);
    }

    public function removestagiaire(Stagiaire $stagiaire): self
    {
        if ($this->stagiaires->contains($stagiaire)) {
            $this->stagiaires->removeElement($stagiaire);
            $stagiaire->removeSession($this);
        }

        return $this;
    }

    /**
     * @return Collection|Programme[]
     * 
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->contains($programme)) {
            $this->programmes->removeElement($programme);
            // set the owning side to null (unless already changed)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vacances[]
     */
    public function getVacances(): Collection
    {
        return $this->vacances;
    }

    public function addVacance(Vacances $vacance): self
    {
        if (!$this->vacances->contains($vacance)) {
            $this->vacances[] = $vacance;
            $vacance->setSession($this);
        }

        return $this;
    }

    public function removeVacance(Vacances $vacance): self
    {
        if ($this->vacances->contains($vacance)) {
            $this->vacances->removeElement($vacance);
            // set the owning side to null (unless already changed)
            if ($vacance->getSession() === $this) {
                $vacance->setSession(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }

    
}
