<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID;

    #[ORM\ManyToOne(targetEntity: Friends::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true)]
    private $friends_ID;

    #[ORM\Column(type: 'text')]
    private $message;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created;

    public function __construct()
    {
        $this->created = new \DateTime("now");
    }

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

    public function getFriendsID(): ?Friends
    {
        return $this->friends_ID;
    }

    public function setFriendsID(?Friends $friends_ID): self
    {
        $this->friends_ID = $friends_ID;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
    public function __toString()
    {
        
        return  $this->message; 
    }
}
