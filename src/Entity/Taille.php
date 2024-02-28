<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TailleRepository::class)
 */
class Taille
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="surface")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metre;

    /**
     * @ORM\Column(type="float")
     */
    private $objectif;

    public function __construct(string $nom, string $metre, float $obj)
    {
        $this->users = new ArrayCollection();
        $this->nom = $nom;
        $this->metre = $metre;
        $this->objectif =$obj;
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSurface($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSurface() === $this) {
                $user->setSurface(null);
            }
        }

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
}
