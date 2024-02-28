<?php

namespace App\Entity;

use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FriendRepository::class)
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_friend;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followed")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_followed;

    public function __construct(?User $user_co = null, ?User $user = null)
    {
        $this->user_followed = $user;
        $this->user_friend = $user_co;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFriend(): ?user
    {
        return $this->user_friend;
    }

    public function setUserFriend(?user $user_friend): self
    {
        $this->user_friend = $user_friend;

        return $this;
    }

    public function getUserFollowed(): ?user
    {
        return $this->user_followed;
    }

    public function setUserFollowed(?user $user_followed): self
    {
        $this->user_followed = $user_followed;

        return $this;
    }
}
