<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'achats')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $descritpion;

    #[ORM\Column(type: 'datetime')]
    private $createdat;

    #[ORM\OneToOne(targetEntity: Flux::class, inversedBy: 'achat', cascade: ['persist', 'remove'])]
    private $flux;

    #[ORM\Column(type: 'boolean')]
    private $shared;

    #[ORM\Column(type: 'integer')]
    private $quantite;

    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'achats')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

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

    public function getDescritpion(): ?string
    {
        return $this->descritpion;
    }

    public function setDescritpion(?string $descritpion): self
    {
        $this->descritpion = $descritpion;

        return $this;
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

    public function getShared(): ?bool
    {
        return $this->shared;
    }

    public function setShared(bool $shared): self
    {
        $this->shared = $shared;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getFlux(): ?flux
    {
        return $this->flux;
    }

    public function setFlux(?flux $flux): self
    {
        $this->flux = $flux;

        return $this;
    }

}
