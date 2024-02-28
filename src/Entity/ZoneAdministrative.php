<?php

namespace App\Entity;

use App\Repository\ZoneAdministrativeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneAdministrativeRepository::class)
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(name="zone_administrative_unique", columns={"country_code", "name"})
 * })
 */
class ZoneAdministrative
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="administrativeArea")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meteolink;

    public function __construct(string $countryCode, string $name)
    {
        $this->countryCode = $countryCode;
        $this->name = $name;
        $this->users = new ArrayCollection();
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
            $user->setAdministrativeArea($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAdministrativeArea() === $this) {
                $user->setAdministrativeArea(null);
            }
        }

        return $this;
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
}
