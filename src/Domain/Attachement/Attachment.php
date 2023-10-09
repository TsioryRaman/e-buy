<?php

namespace App\Domain\Attachement;

use App\Domain\Article\Article;
use App\Repository\Domain\Attachement\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = '';

    #[Vich\UploadableField(mapping: 'articles', fileNameProperty: 'filename')]
    private ?File $imageFile = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'attachment')]
    private ?Article $article = null;

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }
}
