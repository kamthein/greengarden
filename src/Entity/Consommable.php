<?php

namespace App\Entity;

use App\Repository\ConsommableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use RuntimeException;

/**
 * @ORM\Entity(repositoryClass=ConsommableRepository::class)
 *
 * @Gedmo\Tree(type="nested")
 */
class Consommable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $treeLeft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $treeLevel;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $treeRight;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity=Consommable::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Consommable::class, mappedBy="parent")
     * @ORM\OrderBy({"treeLeft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon_lien;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $badge1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $badge2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $badge3;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $calorie;

        /**
     * @ORM\OneToMany(targetEntity=Recolte::class, mappedBy="consommable")
     */
    private $recoltes;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="consommable")
     */
    private $plants;


    public function __construct(string $name = null, ?self $parent = null)
    {
        $this->recoltes = new ArrayCollection();
        $this->plants = new ArrayCollection();

        if ($name) {
            $this->setNom($name);
            $this->setDescription($name);
        }

        if ($parent instanceof \App\Entity\Consommable) {
            $this->setParent($parent);
        }
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIconLien(): ?string
    {
        return $this->icon_lien;
    }

    public function setIconLien(?string $icon_lien): self
    {
        $this->icon_lien = $icon_lien;

        return $this;
    }

    public function getBadge1(): ?string
    {
        return $this->badge1;
    }

    public function setBadge1(?string $badge1): self
    {
        $this->badge1 = $badge1;

        return $this;
    }

    public function getBadge2(): ?string
    {
        return $this->badge2;
    }

    public function setBadge2(string $badge2): self
    {
        $this->badge2 = $badge2;

        return $this;
    }

    public function getBadge3(): ?string
    {
        return $this->badge3;
    }

    public function setBadge3(?string $badge3): self
    {
        $this->badge3 = $badge3;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCalorie(): ?float
    {
        return $this->calorie;
    }

    public function setCalorie(?float $calorie): self
    {
        $this->calorie = $calorie;

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
            $recolte->setConsommable($this);
        }

        return $this;
    }

    public function removeRecolte(Recolte $recolte): self
    {
        if ($this->recoltes->contains($recolte)) {
            $this->recoltes->removeElement($recolte);
            // set the owning side to null (unless already changed)
            if ($recolte->getConsommable() === $this) {
                $recolte->setConsommable(null);
            }
        }

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
            $plant->setConsommable($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        // set the owning side to null (unless already changed)
        if ($this->plants->removeElement($plant) && $plant->getConsommable() === $this) {
            $plant->setConsommable(null);
        }

        return $this;
    }

    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    public function getTreeRight()
    {
        return $this->treeRight;
    }

    public function setParent(self $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParentAtLevel(int $level): self
    {
        if ($this->treeLevel === 0) {
            throw new RuntimeException('Cannot get parent on root node!');
        }

        if ($this->treeLevel === 1) {
            return $this;
        }

        if ($this->treeLevel === 2) {
            return $this->parent;
        }

        // niveau 3+
        $parent = $this->parent;
        while ($parent->getTreeLevel() !== 1) {
            $parent = $parent->getParent();
        }

        return $parent;
    }

}
