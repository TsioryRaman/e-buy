<?php

namespace App\Domain\Commande\Entity;

use App\Domain\Article\Article;
use App\Domain\Auth\User;
use App\Domain\Delivery\Entity\Delivery;
use App\Repository\Domain\Commande\Entity\CommandeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

 #[ORM\Entity(repositoryClass:CommandeRepository::class)]

class Commande
{


     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:Types::INTEGER)]
    private int $id;


     #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"commandes")]
    private User $user;


    #[ORM\OneToMany(mappedBy: "commande", targetEntity: Article::class)]
    private $article;


    #[ORM\Column(type:Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $date_commande;


     #[ORM\ManyToOne(targetEntity:Delivery::class, inversedBy:"commande")]
    private Delivery $delivery;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->date_commande = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $article->setCommande($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCommande() === $this) {
                $article->setCommande(null);
            }
        }

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
}
