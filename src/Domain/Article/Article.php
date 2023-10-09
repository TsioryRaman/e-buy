<?php

namespace App\Domain\Article;

use App\Domain\Attachement\Attachment;
use App\Domain\Category;
use App\Domain\Commande\Entity\Commande;
use App\Domain\Fournisseur\Entity\Fournisseur;
use App\Domain\Store\Entity\Store;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $updated_at;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "articles")]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    #[ORM\Column(type: Types::INTEGER)]
    private int $price;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $address;

    #[ORM\Column(type: Types::BOOLEAN, length: 255)]
    private bool $sold = false;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $PostalCode;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "article")]
    private ?Commande $commande;

    #[ORM\ManyToMany(targetEntity: Store::class, mappedBy: "article")]
    private $stores;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $quantity = 0;

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: "article")]
    private Fournisseur $fournisseur;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $brand = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Attachment::class, cascade: ['persist', 'remove'])]
    private Collection $attachment;

    public function __construct()
    {
        $this->attachment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->PostalCode;
    }

    public function setPostalCode(string $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * @return Collection<int, Store>
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    public function addStore(Store $store): self
    {
        if (!$this->stores->contains($store)) {
            $this->stores[] = $store;
            $store->addArticle($this);
        }

        return $this;
    }

    public function removeStore(Store $store): self
    {
        if ($this->stores->removeElement($store)) {
            $store->removeArticle($this);
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachment(): Collection
    {
        return $this->attachment;
    }

    public function addAttachment(Attachment $attachment): static
    {
        if (!$this->attachment->contains($attachment)) {
            $this->attachment->add($attachment);
            $attachment->setArticle($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): static
    {
        if ($this->attachment->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getArticle() === $this) {
                $attachment->setArticle(null);
            }
        }

        return $this;
    }
}
