<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedat;

    /**
     * @ORM\OneToMany(targetEntity=Recolte::class, mappedBy="panier", cascade={"persist", "remove"})
     */
    private $recoltes;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="panier", cascade={"persist", "remove"})
     */
    private $plants;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="panier", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $shared;

    /**
     * @ORM\OneToOne(targetEntity=Flux::class, inversedBy="panier", cascade={"persist", "remove"})
     */
    private $flux;




    public function __construct()
    {
        $this->recoltes = new ArrayCollection();
        $this->photo = new ArrayCollection();
        $this->plants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedat(): ?DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

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
            $recolte->setPanier($this);
        }

        return $this;
    }

    public function removeRecolte(Recolte $recolte): self
    {
        if ($this->recoltes->contains($recolte)) {
            $this->recoltes->removeElement($recolte);
            // set the owning side to null (unless already changed)
            if ($recolte->getPanier() === $this) {
                $recolte->setPanier(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection|photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setPanier($this);
        }

        return $this;
    }

    public function removePhoto(photo $photo): self
    {
        if ($this->photo->contains($photo)) {
            $this->photo->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getPanier() === $this) {
                $photo->setPanier(null);
            }
        }

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

    public function getFlux(): ?flux
    {
        return $this->flux;
    }

    public function setFlux(?flux $flux): self
    {
        $this->flux = $flux;

        return $this;
    }

    /**
     * @return Collection|Plant[]
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants[] = $plant;
            $plant->setPanier($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        // set the owning side to null (unless already changed)
        if ($this->plants->removeElement($plant) && $plant->getPanier() === $this) {
            $plant->setPanier(null);
        }

        return $this;
    }


}
