<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvatarsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: AvatarsRepository::class)]
class Avatars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[File( mimeTypes:["image/png", "image/jpeg"], mimeTypesMessage:"le format de fichier ne correspond pas")]
    private $picture;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $pseudo;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'array', nullable: true)]
    private $options = [];

    #[ORM\Column(type: 'integer', nullable: true)]
    private $age;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private $cat;

    #[ORM\Column(type: 'boolean')]
    private $is_verified;

    #[ORM\OneToOne(inversedBy: 'avatar', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user_ID;

    #[ORM\OneToMany(mappedBy: 'avatar_ID', targetEntity: Articles::class)]
    private $articles;

    #[ORM\OneToMany(mappedBy: 'avatar_ID', targetEntity: Comments::class)]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'avatar_ID', targetEntity: InfosAvatar::class)]
    private $infosAvatar;

    #[ORM\OneToMany(mappedBy: 'avatar_ID1', targetEntity: Friends::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private $friends;

    #[ORM\OneToMany(mappedBy: 'avatar_ID', targetEntity: Messages::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private $messages;

    #[ORM\OneToMany(mappedBy: 'avatar_ID', targetEntity: Notes::class, orphanRemoval: true)]
    
    private $notes;


    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->infosAvatar = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->setPseudo("Nouveaux membre");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture( $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    #[ORM\PostRemove]
    public function deletePicture()
    {
        if (file_exists(__DIR__.'/../../public/upload/'. $this->picture)) {
            unlink(__DIR__.'/../../public/upload/'. $this->picture);
        }
        return true;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

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

    public function getCat(): ?string
    {
        return $this->cat;
    }

    public function setCat(?string $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getUserID(): ?User
    {
        return $this->user_ID;
    }

    public function setUserID(User $user_ID): self
    {
        $this->user_ID = $user_ID;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAvatarID($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAvatarID() === $this) {
                $article->setAvatarID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAvatarID($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAvatarID() === $this) {
                $comment->setAvatarID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InfosAvatar>
     */
    public function getInfosAvatar(): Collection
    {
        return $this->infosAvatar;
    }

    public function addInfosAvatar(InfosAvatar $infosAvatar): self
    {
        if (!$this->infosAvatar->contains($infosAvatar)) {
            $this->infosAvatar[] = $infosAvatar;
            $infosAvatar->setAvatarID($this);
        }

        return $this;
    }

    public function removeInfosAvatar(InfosAvatar $infosAvatar): self
    {
        if ($this->infosAvatar->removeElement($infosAvatar)) {
            // set the owning side to null (unless already changed)
            if ($infosAvatar->getAvatarID() === $this) {
                $infosAvatar->setAvatarID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friends>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friends $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->setAvatarID1($this);
        }

        return $this;
    }

    public function removeFriend(Friends $friend): self
    {
        if ($this->friends->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getAvatarID1() === $this) {
                $friend->setAvatarID1(null);
            }
        }

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
            $message->setAvatarID($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAvatarID() === $this) {
                $message->setAvatarID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setAvatarID($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getAvatarID() === $this) {
                $note->setAvatarID(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        
        return  $this->pseudo; 
    }
}
