<?php

namespace App\Http\Admin\Data;

use App\Domain\Article\Article;
use App\Domain\Category;
use App\Domain\Commande\Entity\Commande;
use App\Domain\Fournisseur\Entity\Fournisseur;
use App\Form\ArticleType;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleCrudData implements CrudDataInterface
{
    public ?string $description;

    public ?\DateTimeImmutable $createdAt = null;

    public ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotNull]
    public Category $category;

    #[Assert\Type(type: Types::INTEGER)]
    #[Assert\NotNull]
    public int $price;

    public string $address;

    public bool $sold = false;

    public string $postalCode;

    public ?Commande $commande;

    public $stores;

    public int $quantity = 0;

    public Fournisseur $fournisseur;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\File()]
    public ?UploadedFile $imageFile = null;

    public ?string $filename = '';

    #[Assert\NotNull]
    public ?string $brand = null;

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
        $data->updatedAt = $article->getUpdatedAt();
        $data->createdAt = $article->getCreatedAt();
        $data->quantity = $article->getQuantity();
        $data->category = $article->getCategory();
        $data->imageFile = $article->getImageFile();
        $data->filename = $article->getFilename();

        return $data;

    }

    public function hydrate():void
    {
        $this->entity
            ->setImageFile($this->imageFile)
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
            ->setUpdatedAt(new \DateTimeImmutable());


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