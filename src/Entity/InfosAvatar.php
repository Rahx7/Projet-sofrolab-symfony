<?php

namespace App\Entity;

use App\Repository\InfosAvatarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosAvatarRepository::class)]
class InfosAvatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'infosAvatar')]
    private $avatar_ID;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $key_info;

    #[ORM\Column(type: 'text', nullable: true)]
    private $data;

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

    public function getKeyInfo(): ?string
    {
        return $this->key_info;
    }

    public function setKeyInfo(?string $key_info): self
    {
        $this->key_info = $key_info;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }
    public function __toString()
    {
        
        return  $this->key_info; 
    }
}
