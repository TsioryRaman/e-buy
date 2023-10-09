<?php

namespace App\Domain\Fournisseur\Entity;

use App\Domain\Fournisseur\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Article\Article;

use Symfony\Component\Validator\Constraints as Assert;


 #[ORM\Entity(repositoryClass:FournisseurRepository::class)]
class Fournisseur
{
    public function __toString()
    {
        return $this->name;
    }


     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:Types::INTEGER)]
    private int $id;


     #[ORM\Column(type:Types::STRING, length:255)]
     #[Assert\Length(min:2, max:255)]
    private string $name;


    #[ORM\OneToMany(mappedBy: "fournisseur", targetEntity: Article::class)]
    private $article;

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
            $article->setFournisseur($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getFournisseur() === $this) {
                $article->setFournisseur(null);
            }
        }

        return $this;
    }
}
