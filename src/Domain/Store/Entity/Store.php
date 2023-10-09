<?php

namespace App\Domain\Store\Entity;

use App\Domain\Article\Article;
use App\Repository\Domain\Store\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


 #[ORM\Entity(repositoryClass:StoreRepository::class)]
class Store
{

     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:Types::INTEGER)]
    private int $id;


     #[ORM\Column(type:Types::STRING, length:255)]
    private string $name;


     #[ORM\ManyToMany(targetEntity:Article::class, inversedBy:"stores")]
    private Collection $article;

    #[ORM\Column(type:Types::STRING, length:255)]
    private string $address;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->article->removeElement($article);

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
