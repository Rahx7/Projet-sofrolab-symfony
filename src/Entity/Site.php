<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $intro1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $title1;

    #[ORM\Column(type: 'text', nullable: true)]
    private $intro;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle3;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text1;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text2;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text3;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle11;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle22;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle33;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntro1(): ?string
    {
        return $this->intro1;
    }

    public function setIntro1(?string $intro1): self
    {
        $this->intro1 = $intro1;

        return $this;
    }

    public function getTitle1(): ?string
    {
        return $this->title1;
    }

    public function setTitle1(?string $title1): self
    {
        $this->title1 = $title1;

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

    public function getSubtitle1(): ?string
    {
        return $this->subtitle1;
    }

    public function setSubtitle1(?string $subtitle1): self
    {
        $this->subtitle1 = $subtitle1;

        return $this;
    }

    public function getSubtitle2(): ?string
    {
        return $this->subtitle2;
    }

    public function setSubtitle2(?string $subtitle2): self
    {
        $this->subtitle2 = $subtitle2;

        return $this;
    }

    public function getSubtitle3(): ?string
    {
        return $this->subtitle3;
    }

    public function setSubtitle3(?string $subtitle3): self
    {
        $this->subtitle3 = $subtitle3;

        return $this;
    }

    public function getText1(): ?string
    {
        return $this->text1;
    }

    public function setText1(?string $text1): self
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(?string $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getText3(): ?string
    {
        return $this->text3;
    }

    public function setText3(?string $text3): self
    {
        $this->text3 = $text3;

        return $this;
    }

    public function getSubtitle11(): ?string
    {
        return $this->subtitle11;
    }

    public function setSubtitle11(?string $subtitle11): self
    {
        $this->subtitle11 = $subtitle11;

        return $this;
    }

    public function getSubtitle22(): ?string
    {
        return $this->subtitle22;
    }

    public function setSubtitle22(?string $subtitle22): self
    {
        $this->subtitle22 = $subtitle22;

        return $this;
    }

    public function getSubtitle33(): ?string
    {
        return $this->subtitle33;
    }

    public function setSubtitle33(?string $subtitle33): self
    {
        $this->subtitle33 = $subtitle33;

        return $this;
    }
}
