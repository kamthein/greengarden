<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="likes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=flux::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $flux;

    public function __construct(?Flux $flux = null, ?User $user = null)
    {
        $this->flux = $flux;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
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
