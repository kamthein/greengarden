<?php

namespace App\Entity;

use App\Repository\MethodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MethodeRepository::class)
 */
class Methode
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
     * @ORM\OneToMany(targetEntity=Recolte::class, mappedBy="methode")
     */
    private $recoltes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_lien;


    public function __construct(string $nom, string $image_lien)
    {
        $this->recoltes = new ArrayCollection();
        $this->nom = $nom;
        $this->image_lien = $image_lien;
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
     * @return Collection|Recolte[]
     */
    public function getRecoltes(): Collection
    {
        return $this->recoltes;
    }

    public function addRecolte(Recolte $recolte): self
    {
        if (!$this->recoltes->contains($recolte)) {
            $this->recoltes[] = $recolte;
            $recolte->setMethode($this);
        }

        return $this;
    }

    public function removeRecolte(Recolte $recolte): self
    {
        if ($this->recoltes->contains($recolte)) {
            $this->recoltes->removeElement($recolte);
            // set the owning side to null (unless already changed)
            if ($recolte->getMethode() === $this) {
                $recolte->setMethode(null);
            }
        }

        return $this;
    }

    public function getImageLien(): ?string
    {
        return $this->image_lien;
    }

    public function setImageLien(?string $image_lien): self
    {
        $this->image_lien = $image_lien;

        return $this;
    }
}
