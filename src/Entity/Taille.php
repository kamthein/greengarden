<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
class Taille
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    public $users;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?string $metre = null;

    #[ORM\Column(nullable: true)]
    private ?float $objectif = null;

    #[ORM\OneToMany(mappedBy: 'surface', targetEntity: Garden::class)]
    private Collection $taille;

    public function __construct(string $nom, string $metre, float $obj)
    {
        $this->users = new ArrayCollection();
        $this->nom = $nom;
        $this->metre = $metre;
        $this->objectif =$obj;
        $this->taille = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nom;
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

    public function getMetre(): ?string
    {
        return $this->metre;
    }

    public function setMetre(?string $metre): self
    {
        $this->metre = $metre;

        return $this;
    }

    public function getObjectif(): ?float
    {
        return $this->objectif;
    }

    public function setObjectif(float $objectif): self
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * @return Collection<int, Garden>
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(Garden $taille): static
    {
        if (!$this->taille->contains($taille)) {
            $this->taille->add($taille);
            $taille->setSurface($this);
        }

        return $this;
    }

    public function removeTaille(Garden $taille): static
    {
        // set the owning side to null (unless already changed)
        if ($this->taille->removeElement($taille) && $taille->getSurface() === $this) {
            $taille->setSurface(null);
        }

        return $this;
    }
}
