<?php

namespace App\Entity;

use App\Repository\FluxRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FluxRepository::class)
 */
class Flux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedat;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="fluxes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\Column(type="boolean")
     */
    private $shared;

    /**
     * @ORM\OneToOne(targetEntity=Post::class, mappedBy="flux", cascade={"persist", "remove"})
     */
    private $post;


        /**
     * @ORM\OneToOne(targetEntity=Achat::class, mappedBy="flux", cascade={"persist", "remove"})
     */
    private $achat;

    /**
     * @ORM\OneToOne(targetEntity=Panier::class, mappedBy="flux", cascade={"persist", "remove"})
     */
    private $panier;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="flux", cascade={"persist", "remove"})
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="flux", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();

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

    public function getUpdatedat(): ?DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

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

    public function getShared(): ?bool
    {
        return $this->shared;
    }

    public function setShared(bool $shared): self
    {
        $this->shared = $shared;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        // set (or unset) the owning side of the relation if necessary
        $newFlux = null === $post ? null : $this;
        if ($post->getFlux() !== $newFlux) {
            $post->setFlux($newFlux);
        }

        return $this;
    }

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): self
    {
        $this->achat = $achat;

        // set (or unset) the owning side of the relation if necessary
        $newFlux = null === $achat ? null : $this;
        if ($achat->getFlux() !== $newFlux) {
            $achat->setFlux($newFlux);
        }

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        // set (or unset) the owning side of the relation if necessary
        $newFlux = null === $panier ? null : $this;
        if ($panier->getFlux() !== $newFlux) {
            $panier->setFlux($newFlux);
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFlux($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFlux() === $this) {
                $commentaire->setFlux(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setFlux($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getFlux() === $this) {
                $like->setFlux(null);
            }
        }

        return $this;
    }


}
