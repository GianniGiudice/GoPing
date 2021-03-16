<?php

namespace App\Entity;

use App\Repository\ReactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReactionRepository::class)
 */
class Reaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class_ok;

    /**
     * @ORM\OneToMany(targetEntity=PublicationReaction::class, mappedBy="reaction")
     */
    private $publicationReactions;

    public function __construct()
    {
        $this->publicationReactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getClassOk(): ?string
    {
        return $this->class_ok;
    }

    public function setClassOk(string $class_ok): self
    {
        $this->class_ok = $class_ok;

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
            $publicationReaction->setReaction($this);
        }

        return $this;
    }

    public function removePublicationReaction(PublicationReaction $publicationReaction): self
    {
        if ($this->publicationReactions->removeElement($publicationReaction)) {
            // set the owning side to null (unless already changed)
            if ($publicationReaction->getReaction() === $this) {
                $publicationReaction->setReaction(null);
            }
        }

        return $this;
    }
}
