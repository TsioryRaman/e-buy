<?php

namespace App\Http\Admin\Data;

use App\Domain\Category;
use App\Form\CategoryType;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryCrudData implements CrudDataInterface
{
    #[Assert\NotNull]
    #[Assert\Length(min: 5,max: 255)]
    public string $name;

    public string $description;

    #[Assert\NotNull]
    public \DateTimeInterface $created_at;

    #[Assert\NotNull]
    public \DateTimeInterface $updated_at;

    public Category $entity;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getForm(): string
    {
        return CategoryType::class;
    }

    public function hydrate(): void
    {
        // $this->name = $this->entity->getName();
        // $this->description = $this->entity->getDescription();
        $this->entity
            ->setName($this->name)
            ->setDescription($this->description)
            ->setCreatedAt($this->created_at)
            ->setUpdatedAt($this->updated_at);
    }

    public static function makeFromPost(Category $category):self{
        $data = new self();
        $data->name = $category->getName();
        $data->description = $category->getDescription();

        return $data;
    }

    public function hydrateNewEntity(): void
    {
        $this->hydrate();
        $this->entity->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }
}