<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UniverseRepository")
 */
class Universe
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
    private $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resumeUniverse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortResume;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Community", mappedBy="universe", orphanRemoval=true)
     */
    private $communities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Personnage", mappedBy="universe", orphanRemoval=true)
     */
    private $personnages;

    public function __construct()
    {
        $this->communities = new ArrayCollection();
        $this->personnages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getResumeUniverse(): ?string
    {
        return $this->resumeUniverse;
    }

    public function setResumeUniverse(?string $resumeUniverse): self
    {
        $this->resumeUniverse = $resumeUniverse;

        return $this;
    }

    public function getShortResume(): ?string
    {
        return $this->shortResume;
    }

    public function setShortResume(?string $shortResume): self
    {
        $this->shortResume = $shortResume;

        return $this;
    }

    public function getRules(): ?string
    {
        return $this->rules;
    }

    public function setRules(?string $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return Collection|Community[]
     */
    public function getCommunities(): Collection
    {
        return $this->communities;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getCommunityByUser($user){
        foreach ($this->getCommunities()->toArray() as $community){
            if($community->getUser() === $user){
                return $community;
            }
        }
    }

    public function addCommunity(Community $community): self
    {
        if (!$this->communities->contains($community)) {
            $this->communities[] = $community;
            $community->setUniverse($this);
        }

        return $this;
    }

    public function removeCommunity(Community $community): self
    {
        if ($this->communities->contains($community)) {
            $this->communities->removeElement($community);
            // set the owning side to null (unless already changed)
            if ($community->getUniverse() === $this) {
                $community->setUniverse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personnage[]
     */
    public function getPersonnages(): Collection
    {
        return $this->personnages;
    }

    public function addPersonnage(Personnage $personnage): self
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages[] = $personnage;
            $personnage->setUniverse($this);
        }

        return $this;
    }

    public function removePersonnage(Personnage $personnage): self
    {
        if ($this->personnages->contains($personnage)) {
            $this->personnages->removeElement($personnage);
            // set the owning side to null (unless already changed)
            if ($personnage->getUniverse() === $this) {
                $personnage->setUniverse(null);
            }
        }

        return $this;
    }
}
