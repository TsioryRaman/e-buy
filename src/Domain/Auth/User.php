<?php

namespace App\Domain\Auth;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleView;
use App\Domain\Cart\Cart;
use App\Domain\Commande\Entity\Commande;
use App\Domain\Auth\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;


    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    #[Assert\Email(
        message: "L'email '{{ value }}' n'est pas un email valide"
    )]
    #[Assert\NotBlank]
    private string $email;


    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Vous devez entrer un mot de passe superieur a 5 caracters",
        maxMessage: "Votre mot de passe ne doit pas depasser 255 caracteres"
    )]
    private string $password;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'view_by', targetEntity: ArticleView::class)]
    private ?Collection $article_view = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'like_by')]
    private Collection $article_liked;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cart::class, orphanRemoval: true)]
    private Collection $carts;

    #[ORM\Column(length: 255)]
    private ?string $theme = 'light';

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->article_liked = new ArrayCollection();
        $this->article_view = new ArrayCollection();
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function eraseCredentials()
    {

    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, ArticleView>
     */

    /**
     * @return Collection<int, Article>
     */
    public function getArticleLiked(): Collection
    {
        return $this->article_liked;
    }

    public function addArticleLiked(Article $articleLiked): static
    {
        if (!$this->article_liked->contains($articleLiked)) {
            $this->article_liked->add($articleLiked);
            $articleLiked->addLikeBy($this);
        }

        return $this;
    }

    public function removeArticleLiked(Article $articleLiked): static
    {
        if ($this->article_liked->removeElement($articleLiked)) {
            $articleLiked->removeLikeBy($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getArticleView(): ?Collection
    {
        return $this->article_view;
    }

    public function addArticleView(ArticleView $article_view): static
    {
        if (!$this->article_view->contains($article_view)) {
            $this->article_view->add($article_view);
            $article_view->setViewBy($this);
        }

        return $this;
    }

    public function removeArticleView(ArticleView $article_view): static
    {
        if ($this->article_view->removeElement($article_view)) {
            // set the owning side to null (unless already changed)
            if ($article_view->getArticle() === $this) {
                $article_view->setArticle(null);
            }
        }

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

}
