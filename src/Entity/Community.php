<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommunityRepository")
 */
class Community
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="communities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Universe", inversedBy="communities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $universe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statut", inversedBy="communities")
     * @ORM\JoinColumn(nullable=true)
     */
    private $statut;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
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

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
