<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentsRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $comment;

    #[ORM\Column(type: 'boolean')]
    private $is_verified;

    #[ORM\Column(type: 'datetime')]
    private $created;

    #[ORM\ManyToOne(targetEntity: Avatars::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private $avatar_ID;

    #[ORM\ManyToOne(targetEntity: Articles::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private $article_ID;

    // private $user;

    public function __construct()
    {
        // $request = new Request;

        // dump($this);
        $this->created = new \DateTime('now');

        // $this->avatard_ID = $request->getUser();
        // $this->user = $user;
        
        // $this->session = $session;
        //$user = $session->get_current_user;
        // $this->avatar_ID = $user->getUserIdentifier();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    public function __toString()
    { 
        return  $this->id; 
    }
}
