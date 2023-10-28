<?php

namespace App\Domain\Article;

use App\Repository\Domain\Article\ArticleViewRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Auth\User;
use \App\Domain\Article\Article;

#[ORM\Entity(repositoryClass: ArticleViewRepository::class)]
class ArticleView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    private ?User $view_by = null;

    #[ORM\ManyToOne(inversedBy: 'articleViews')]
    private ?Article $article = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getViewBy(): ?User
    {
        return $this->view_by;
    }

    public function setViewBy(?User $view_by): static
    {
        $this->view_by = $view_by;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
