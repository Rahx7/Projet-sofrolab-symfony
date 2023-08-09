<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendsRepository::class)]
class Friends
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'friends')]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID1;

    #[ORM\ManyToOne(targetEntity: Avatars::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID2;

    #[ORM\Column(type: 'boolean')]
    private $verified;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created;

    #[ORM\OneToMany(mappedBy: 'friends_ID', targetEntity: Messages::class)]
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->created = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarID1(): ?Avatars
    {
        return $this->avatar_ID1;
    }

    public function setAvatarID1(?Avatars $avatar_ID1): self
    {
        $this->avatar_ID1 = $avatar_ID1;

        return $this;
    }

    public function getAvatarID2(): ?Avatars
    {
        return $this->avatar_ID2;
    }

    public function setAvatarID2(?Avatars $avatar_ID2): self
    {
        $this->avatar_ID2 = $avatar_ID2;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

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

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setFriendsID($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getFriendsID() === $this) {
                $message->setFriendsID(null);
            }
        }

        return $this;
    }

    public function __toString()
    { 
        return  $this->id; 
    }

}
