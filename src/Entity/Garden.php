<?php

namespace App\Entity;

use App\Repository\GardenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GardenRepository::class)]
class Garden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $sable = null;

    #[ORM\Column(nullable: true)]
    private ?int $argile = null;

    #[ORM\Column(nullable: true)]
    private ?int $calcaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $limon = null;

    #[ORM\Column(nullable: true)]
    private ?int $serre = null;

    #[ORM\Column(nullable: true)]
    private ?int $cuve = null;

    #[ORM\Column(nullable: true)]
    private ?int $minpluvio = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxpluvio = null;

    #[ORM\Column(nullable: true)]
    private ?int $moyenneprod = null;

    #[ORM\ManyToOne(inversedBy: 'taille', fetch:"EAGER")]
    private ?Taille $surface = null;

    #[ORM\ManyToOne(inversedBy: 'gardens', fetch:"EAGER")]
    private ?ZoneAdministrative $region = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'gardens', fetch:"EAGER")]
    private User $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSable(): ?int
    {
        return $this->sable;
    }

    public function setSable(?int $sable): static
    {
        $this->sable = $sable;

        return $this;
    }

    public function getArgile(): ?int
    {
        return $this->argile;
    }

    public function setArgile(?int $argile): static
    {
        $this->argile = $argile;

        return $this;
    }

    public function getCalcaire(): ?int
    {
        return $this->calcaire;
    }

    public function setCalcaire(?int $calcaire): static
    {
        $this->calcaire = $calcaire;

        return $this;
    }

    public function getLimon(): ?int
    {
        return $this->limon;
    }

    public function setLimon(?int $limon): static
    {
        $this->limon = $limon;

        return $this;
    }

    public function getSerre(): ?int
    {
        return $this->serre;
    }

    public function setSerre(?int $serre): static
    {
        $this->serre = $serre;

        return $this;
    }

    public function getCuve(): ?int
    {
        return $this->cuve;
    }

    public function setCuve(?int $cuve): static
    {
        $this->cuve = $cuve;

        return $this;
    }

    public function getMinpluvio(): ?int
    {
        return $this->minpluvio;
    }

    public function setMinpluvio(?int $minpluvio): static
    {
        $this->minpluvio = $minpluvio;

        return $this;
    }

    public function getMaxpluvio(): ?int
    {
        return $this->maxpluvio;
    }

    public function setMaxpluvio(?int $maxpluvio): static
    {
        $this->maxpluvio = $maxpluvio;

        return $this;
    }

    public function getMoyenneprod(): ?int
    {
        return $this->moyenneprod;
    }

    public function setMoyenneprod(?int $moyenneprod): static
    {
        $this->moyenneprod = $moyenneprod;

        return $this;
    }

    public function getSurface(): ?Taille
    {
        return $this->surface;
    }

    public function setSurface(?Taille $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRegion(): ?ZoneAdministrative
    {
        return $this->region;
    }

    public function setRegion(?ZoneAdministrative $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }


}
