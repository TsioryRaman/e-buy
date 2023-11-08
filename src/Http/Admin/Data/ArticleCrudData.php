<?php

namespace App\Http\Admin\Data;

use App\Domain\Article\Article;
use App\Domain\Attachement\Attachment;
use App\Domain\Category;
use App\Domain\Commande\Entity\Commande;
use App\Domain\Fournisseur\Entity\Fournisseur;
use App\Form\ArticleType;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleCrudData implements CrudDataInterface
{
    public ?string $description;

    public ?\DateTimeImmutable $created_at = null;

    public ?\DateTimeImmutable $updated_at = null;

    #[Assert\NotNull]
    public Category $category;

    #[Assert\Type(type: Types::INTEGER)]
    #[Assert\NotNull]
    public int $price;

    #[Assert\NotNull]
    public string $address;

    public bool $sold = false;
    #[Assert\NotNull]
    public string $postalCode;

    public ?Commande $commande;

    public $stores;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::INTEGER)]
    public int $quantity = 0;

    public Fournisseur $fournisseur;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotNull]
    public ?string $brand = null;

    public ?Collection $attachment = null;

    public string $slug;

    public ?int $view;

    public Article $entity;

    public static function makeFromPost(Article $article): self {

        $data = new self();
        $data->name = $article->getName();
        $data->brand = $article->getBrand();
        $data->address = $article->getAddress();
        $data->sold = $article->getSold();
        $data->price = $article->getPrice();
        $data->fournisseur = $article->getFournisseur();
        $data->description = $article->getDescription();
        $data->postalCode = $article->getPostalCode();
        $data->updated_at = $article->getUpdatedAt();
        $data->created_at = $article->getCreatedAt();
        $data->quantity = $article->getQuantity();
        $data->category = $article->getCategory();
        $data->attachment = $article->getAttachment();

        return $data;

    }

    public function hydrate():void
    {
        /** @var Attachment $a */
        $this->entity
            ->setName($this->name)
            ->setBrand($this->brand)
            ->setAddress($this->address)
            ->setSold($this->sold)
            ->setPrice($this->price)
            ->setFournisseur($this->fournisseur)
            ->setDescription($this->description)
            ->setPostalCode($this->postalCode)
            ->setQuantity($this->quantity)
            ->setCategory($this->category)
            ->setSlug((new Slugify())->slugify($this->name))
            ->setUpdatedAt(new \DateTimeImmutable());

        foreach ($this->attachment as $a){
            $a->setArticle($this->entity);
        }

    }
    public function hydrateNewEntity():void
    {
        $this->hydrate();
        $this->entity->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getForm(): string
    {
        return ArticleType::class;
    }
}