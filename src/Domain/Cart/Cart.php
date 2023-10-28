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

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartArticle::class)]
    private Collection $cartArticles;

    #[ORM\Column]
    private ?bool $submited = null;

    public function __construct()
    {
        $this->cartArticles = new ArrayCollection();
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

    public function isSubmited(): ?bool
    {
        return $this->submited;
    }

    public function setSubmited(bool $submited): static
    {
        $this->submited = $submited;

        return $this;
    }

}
