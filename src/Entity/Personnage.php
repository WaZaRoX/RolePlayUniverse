<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonnageRepository")
 */
class Personnage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="personnages")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\universe", inversedBy="personnages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $universe;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Kinship", mappedBy="child")
     */
    private $parents;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valide;

    public function __construct()
    {
        $this->enfant = new ArrayCollection();
        $this->mere = new ArrayCollection();
        $this->parent = new ArrayCollection();
        $this->parents = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUniverse(): ?universe
    {
        return $this->universe;
    }

    public function setUniverse(?universe $universe): self
    {
        $this->universe = $universe;

        return $this;
    }

    /**
     * @return Collection|Kinship[]
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(Kinship $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
            $parent->addChild($this);
        }

        return $this;
    }

    public function removeParent(Kinship $parent): self
    {
        if ($this->parents->contains($parent)) {
            $this->parents->removeElement($parent);
            $parent->removeChild($this);
        }

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

}
