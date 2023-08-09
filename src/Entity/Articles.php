<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $sub_title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $intro;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[File( mimeTypes:["audio/ogg", "audio/mpeg", "video/mp4", "audio/mp3", "audio/mpeg3"],mimeTypesMessage:"le format audio de fichier ne correspond pas", ),]
    private $src;

    #[ORM\Column(type: 'text', nullable: true)]
    private $infos;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[File( mimeTypes:["image/png", "image/jpeg"], mimeTypesMessage:"le format de fichier ne correspond pas")]
    private $img;

    #[ORM\Column(type: 'array', nullable: true)]
    private $cat=[];

    #[ORM\Column(type: 'boolean')]
    private $is_verified;

    #[ORM\Column(type: 'datetime')]
    private $created;

    #[ORM\Column(type: 'array', length: 100, nullable: true)]
    private $level=[];

    #[ORM\OneToMany(mappedBy: 'article_ID', targetEntity: Comments::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'article_ID', targetEntity: Notes::class, orphanRemoval: true)]
    private $notes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->created = new \DateTime("now");
        $this->setIsVerified(0);
        
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->sub_title;
    }

    public function setSubTitle(?string $sub_title): self
    {
        $this->sub_title = $sub_title;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): self
    {
        $this->intro = $intro;

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

    public function getSrc()
    {
        return $this->src;
    }

    public function setSrc( $src): self
    {
        $this->src = $src;

        return $this;
    }

    #[ORM\PostRemove]
    public function deleteSrc()
    {
        if (file_exists(__DIR__.'/../../public/upload/'. $this->src)) {
            unlink(__DIR__.'/../../public/upload/'. $this->src);
        }
        return true;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(?string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img): self
    {
        $this->img = $img;

        return $this;
    }

    
    #[ORM\PostRemove]
    public function deleteImg()
    {
        if (file_exists(__DIR__.'/../../public/upload/'. $this->img)) {
            unlink(__DIR__.'/../../public/upload/'. $this->img);
        }
        return true;
    }

    public function getCat(): ?array
    {
        return $this->cat;
    }

    public function setCat(?array $cat): self
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLevel(): ?array
    {
        return $this->level;
    }

    public function setLevel(?array $level): self
    {
        $this->level = $level;

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
            $comment->setArticleID($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticleID() === $this) {
                $comment->setArticleID(null);
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
            $note->setArticleID($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getArticleID() === $this) {
                $note->setArticleID(null);
            }
        }

        return $this;
    }

    
    public function __toString()
    {
        if($this->id != null){
            return  $this->title; 
        }else{
            return  $this->id;
        }
    }

}
