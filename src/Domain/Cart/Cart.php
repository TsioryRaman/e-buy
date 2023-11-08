<?php

namespace App\Domain\Cart;

use App\Domain\Article\Article;
use App\Domain\Auth\User;
use App\Repository\Domain\Cart\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartArticle::class, cascade: ['remove'])]
    private Collection $cartArticles;

    #[ORM\Column]
    private ?bool $submitted = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable('now');
        $this->cartArticles = new ArrayCollection();
        $this->submitted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, CartArticle>
     */
    public function getCartArticles(): Collection
    {
        return $this->cartArticles;
    }

    public function addCartArticle(CartArticle $cartArticle): static
    {
        if (!$this->cartArticles->contains($cartArticle)) {
            $this->cartArticles->add($cartArticle);
            $cartArticle->setCart($this);
        }

        return $this;
    }

    public function removeCartArticle(CartArticle $cartArticle): static
    {
        if ($this->cartArticles->removeElement($cartArticle)) {
            // set the owning side to null (unless already changed)
            if ($cartArticle->getCart() === $this) {
                $cartArticle->setCart(null);
            }
        }

        return $this;
    }

    public function isSubmitted(): ?bool
    {
        return $this->submitted;
    }

    public function setSubmitted(bool $submitted): static
    {
        $this->submitted = $submitted;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
