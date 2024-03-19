<?php

namespace App\Entity;

use App\Repository\ZoneAdministrativeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ZoneAdministrativeRepository::class)]
class ZoneAdministrative
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    public $users;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $countryCode = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $meteolink = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Garden::class)]
    private Collection $gardens;

    public function __construct(string $countryCode, string $name)
    {
        $this->countryCode = $countryCode;
        $this->name = $name;
        $this->users = new ArrayCollection();
        $this->gardens = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function getMeteolink(): ?string
    {
        return $this->meteolink;
    }

    public function setMeteolink(?string $meteolink): self
    {
        $this->meteolink = $meteolink;

        return $this;
    }

    /**
     * @return Collection<int, Garden>
     */
    public function getGardens(): Collection
    {
        return $this->gardens;
    }

    public function addGarden(Garden $garden): static
    {
        if (!$this->gardens->contains($garden)) {
            $this->gardens->add($garden);
            $garden->setRegion($this);
        }

        return $this;
    }

    public function removeGarden(Garden $garden): static
    {
        // set the owning side to null (unless already changed)
        if ($this->gardens->removeElement($garden) && $garden->getRegion() === $this) {
            $garden->setRegion(null);
        }

        return $this;
    }
}
