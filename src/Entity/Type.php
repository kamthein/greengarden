<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{  

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Achat::class)]
    private Collection $achats;

    public function __construct(string $nom)
    {
        $this->achats = new ArrayCollection();
        $this->nom = $nom;
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

    public function addAchat(Achat $achat): static
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setRegion($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): static
    {
        // set the owning side to null (unless already changed)
        if ($this->achats->removeElement($achat)) {
            $achat->setRegion(null);
        }

        return $this;
    }

}
