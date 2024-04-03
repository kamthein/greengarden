<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
class Plant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $createdat;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Consommable::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(nullable: false)]
    private $consommable;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'float')]
    private $quantite;

    #[ORM\ManyToOne(targetEntity: Panier::class, inversedBy: 'plants')]
    private $panier;

    public function __construct()
    {
        $this->createdat = new DateTime('now');
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedat(): ?DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
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

    public function getConsommable(): ?consommable
    {
        return $this->consommable;
    }

    public function setConsommable(?consommable $consommable): self
    {
        $this->consommable = $consommable;

        return $this;
    }


    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }
}
