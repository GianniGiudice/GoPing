<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserDetailsRepository::class)
 */
class UserDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ranking;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $licence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $right_hand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $left_hand;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userDetails", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRanking(): ?float
    {
        return $this->ranking;
    }

    public function setRanking(?float $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(?string $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getRightHand(): ?string
    {
        return $this->right_hand;
    }

    public function setRightHand(?string $right_hand): self
    {
        $this->right_hand = $right_hand;

        return $this;
    }

    public function getLeftHand(): ?string
    {
        return $this->left_hand;
    }

    public function setLeftHand(?string $left_hand): self
    {
        $this->left_hand = $left_hand;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
