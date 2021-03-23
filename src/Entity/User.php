<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cette addresse mail est déjà utilisée.")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà utilisé.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "Votre mot de passe doit comporter au moins {{ limit }} caractères.",
     *     maxMessage = "Votre mot de passe doit comporter moins de {{ limit }} caractères.",
     *     allowEmptyString = false
     * )
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_connection;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "Votre pseudo doit comporter au moins {{ limit }} caractères.",
     *     maxMessage = "Votre pseudo doit comporter moins de {{ limit }} caractères.",
     *     allowEmptyString = false
     * )
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\Type(type="alnum", message = "Votre pseudo ne doit contenir que des caractères alphanumériques.")
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="author")
     */
    private $publications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=PublicationReaction::class, mappedBy="author")
     */
    private $publicationReactions;

    /**
     * @ORM\OneToOne(targetEntity=UserDetails::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userDetails;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->publicationReactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRegistration(): ?\DateTimeInterface
    {
        return $this->registration;
    }

    public function setRegistration(\DateTimeInterface $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->last_connection;
    }

    public function setLastConnection(?\DateTimeInterface $last_connection): self
    {
        $this->last_connection = $last_connection;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setAuthor($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getAuthor() === $this) {
                $publication->setAuthor(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|PublicationReaction[]
     */
    public function getPublicationReactions(): Collection
    {
        return $this->publicationReactions;
    }

    public function addPublicationReaction(PublicationReaction $publicationReaction): self
    {
        if (!$this->publicationReactions->contains($publicationReaction)) {
            $this->publicationReactions[] = $publicationReaction;
            $publicationReaction->setAuthor($this);
        }

        return $this;
    }

    public function removePublicationReaction(PublicationReaction $publicationReaction): self
    {
        if ($this->publicationReactions->removeElement($publicationReaction)) {
            // set the owning side to null (unless already changed)
            if ($publicationReaction->getAuthor() === $this) {
                $publicationReaction->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(UserDetails $userDetails): self
    {
        // set the owning side of the relation if necessary
        if ($userDetails->getUser() !== $this) {
            $userDetails->setUser($this);
        }

        $this->userDetails = $userDetails;

        return $this;
    }
}
