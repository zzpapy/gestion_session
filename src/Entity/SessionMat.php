<?php

namespace App\Entity;

use App\Repository\SessionMatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionMatRepository::class)
 */
class SessionMat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrMat;

    /**
     * @ORM\ManyToMany(targetEntity=Session::class, inversedBy="sessionMats")
     */
    private $session;

    /**
     * @ORM\ManyToMany(targetEntity=ListMat::class, inversedBy="sessionMats")
     */
    private $listMat;

    public function __construct()
    {
        $this->session = new ArrayCollection();
        $this->listMat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrMat(): ?int
    {
        return $this->nbrMat;
    }

    public function setNbrMat(int $nbrMat): self
    {
        $this->nbrMat = $nbrMat;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSession(): Collection
    {
        return $this->session;
    }

    public function addSession(Session $session): self
    {
        if (!$this->session->contains($session)) {
            $this->session[] = $session;
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->session->contains($session)) {
            $this->session->removeElement($session);
        }

        return $this;
    }

    /**
     * @return Collection|ListMat[]
     */
    public function getListMat(): Collection
    {
        return $this->listMat;
    }

    public function addListMat(ListMat $listMat): self
    {
        if (!$this->listMat->contains($listMat)) {
            $this->listMat[] = $listMat;
        }

        return $this;
    }

    public function removeListMat(ListMat $listMat): self
    {
        if ($this->listMat->contains($listMat)) {
            $this->listMat->removeElement($listMat);
        }

        return $this;
    }
}
