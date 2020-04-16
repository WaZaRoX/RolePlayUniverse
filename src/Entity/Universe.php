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

    public function __construct()
    {
        $this->communities = new ArrayCollection();
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
}
