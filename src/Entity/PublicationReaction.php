<?php

namespace App\Entity;

use App\Repository\PublicationReactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationReactionRepository::class)
 */
class PublicationReaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publicationReactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="publicationReactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity=Reaction::class, inversedBy="publicationReactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function getReaction(): ?Reaction
    {
        return $this->reaction;
    }

    public function setReaction(?Reaction $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }
}
