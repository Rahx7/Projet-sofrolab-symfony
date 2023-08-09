<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID;

    #[ORM\ManyToOne(targetEntity: Articles::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true)]
    private $article_ID;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarID(): ?Avatars
    {
        return $this->avatar_ID;
    }

    public function setAvatarID(?Avatars $avatar_ID): self
    {
        $this->avatar_ID = $avatar_ID;

        return $this;
    }

    public function getArticleID(): ?Articles
    {
        return $this->article_ID;
    }

    public function setArticleID(?Articles $article_ID): self
    {
        $this->article_ID = $article_ID;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function __toString()
    {
        
        return  $this->id; 
    }
}
