<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="A user with the same email address already exists.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="Email address is required.", groups={"Registration"})
     * @Assert\Email(groups={"Registration"})
     *
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=15)
     *
     */
    private $nickname;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int", groups={"Profile"})
     *
     */
    private $age;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Recolte::class, mappedBy="user", orphanRemoval=true)
     *
     */
    private $recoltes;

    /**
     * @ORM\OneToMany(targetEntity=Flux::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $fluxes;

    /**
     * @Assert\NotBlank(groups={"Profile"})
     * @UserPassword(groups={"Profile"})
     */
    private $actualPlainPassword;

    /**
     * @Assert\NotBlank(
     *   message="Merci de saisir un mot de passe",
     *   groups={"Registration"}
     * )
     * @Assert\Length(
     *   min=6,
     *   max=120,
     *   minMessage="Votre mot de passe doit comporter au moins {{ limit }} caractères",
     *   groups={"Registration"}
     * )
     */
    private $newPlainPassword;

    /**
     * @ORM\OneToOne(targetEntity=Photo::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="user", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="user", orphanRemoval=true)
     */
    private $plants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_co;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nb_co;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="user_friend")
     */
    private $friends;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="user_followed")
     */
    private $followed;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="user")
     */
    private $achats;

    /**
     * le token qui servira lors de l'oubli de mot de passe
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;
    /**
     *
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $flagResetToken;

    public function __construct()
    {
        $this->setRoles(['ROLE_PUBLISHER']);
        $this->createdAt = new DateTimeImmutable();
        $this->recoltes = new ArrayCollection();
        $this->fluxes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();

        $photo = new Photo;
        $photo->setImageName('avatar.png');
        $photo->setImageSize(236);
        $this->avatar = $photo;
        $this->setAvatar($photo);
        $this->plants = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->followed = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setNewPlainPassword(?string $newPlainPassword): void
    {
        $this->newPlainPassword = $newPlainPassword;
    }

    public function getNewPlainPassword(): ?string
    {
        return $this->newPlainPassword;
    }

    public function getActualPlainPassword(): ?string
    {
        return $this->actualPlainPassword;
    }

    public function setActualPlainPassword(string $actualPlainPassword): void
    {
        $this->actualPlainPassword = $actualPlainPassword;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getUserIdentifier(): string {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() : ?string
    {
        return null;
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $recolte->setUser($this);
        }

        return $this;
    }

    public function removeRecolte(Recolte $recolte): self
    {
        if ($this->recoltes->contains($recolte)) {
            $this->recoltes->removeElement($recolte);
            // set the owning side to null (unless already changed)
            if ($recolte->getUser() === $this) {
                $recolte->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flux[]
     */
    public function getFluxes(): Collection
    {
        return $this->fluxes;
    }

    public function addFlux(Flux $flux): self
    {
        if (!$this->fluxes->contains($flux)) {
            $this->fluxes[] = $flux;
            $flux->setUser($this);
        }

        return $this;
    }

    public function removeFlux(Flux $flux): self
    {
        if ($this->fluxes->contains($flux)) {
            $this->fluxes->removeElement($flux);
            // set the owning side to null (unless already changed)
            if ($flux->getUser() === $this) {
                $flux->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Photo
    {
        return $this->avatar;
    }

    public function setAvatar(?Photo $avatar): self
    {
        $this->avatar = $avatar;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $avatar ? null : $this;
        if ($avatar->getUser() !== $newUser) {
            $avatar->setUser($newUser);
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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
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
            $plant->setUser($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getUser() === $this) {
                $plant->setUser(null);
            }
        }

        return $this;
    }


    public function getLastCo(): ?DateTimeInterface
    {
        return $this->last_co;
    }

    public function setLastCo(?DateTimeInterface $last_co): self
    {
        $this->last_co = $last_co;

        return $this;
    }

    public function getNbCo(): ?float
    {
        return $this->nb_co;
    }

    public function setNbCo(?float $nb_co): self
    {
        $this->nb_co = $nb_co;

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friend $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->setUserFriend($this);
        }

        return $this;
    }

    public function removeFriend(Friend $friend): self
    {
        if ($this->friends->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getUserFriend() === $this) {
                $friend->setUserFriend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getFollowed(): Collection
    {
        return $this->followed;
    }

    public function addFollowed(Friend $followed): self
    {
        if (!$this->followed->contains($followed)) {
            $this->followed[] = $followed;
            $followed->setUserFollowed($this);
        }

        return $this;
    }

    public function removeFollowed(Friend $followed): self
    {
        if ($this->followed->removeElement($followed)) {
            // set the owning side to null (unless already changed)
            if ($followed->getUserFollowed() === $this) {
                $followed->setUserFollowed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setUser($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getUser() === $this) {
                $achat->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return int
     */
    public function getFlagResetToken(): int
    {
        return $this->flagResetToken;
    }

    /**
     * @param int $flagResetToken
     */
    public function setFlagResetToken(int $flagResetToken): void
    {
        $this->flagResetToken = $flagResetToken;
    }


    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

 
}
