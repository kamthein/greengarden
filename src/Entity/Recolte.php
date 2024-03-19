<?php

namespace App\Entity;

use App\Repository\RecolteRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecolteRepository::class)]
class Recolte
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ORM\Column(type: 'datetime')]
    private $createdat;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recoltes')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    
    #[ORM\Column(type: 'float', nullable: true)]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Consommable::class, inversedBy: 'recoltes')]
    #[ORM\JoinColumn(nullable: false)]
    private $consommable;

    #[ORM\ManyToOne(targetEntity: Methode::class, inversedBy: 'recoltes')]
    private $methode;

    #[ORM\ManyToOne(targetEntity: Panier::class, inversedBy: 'recoltes')]
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getMethode(): ?Methode
    {
        return $this->methode;
    }

    public function setMethode(?Methode $methode): self
    {
        $this->methode = $methode;

        return $this;
    }

    public function getPanier(): ?panier
    {
        return $this->panier;
    }

    public function setPanier(?panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

}
