<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KinshipRepository")
 */
class Kinship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\personnage", inversedBy="parents")
     */
    private $child;

    public function __construct()
    {
        $this->child = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|personnage[]
     */
    public function getChild(): Collection
    {
        return $this->child;
    }

    public function addChild(personnage $child): self
    {
        if (!$this->child->contains($child)) {
            $this->child[] = $child;
        }

        return $this;
    }

    public function removeChild(personnage $child): self
    {
        if ($this->child->contains($child)) {
            $this->child->removeElement($child);
        }

        return $this;
    }
}
