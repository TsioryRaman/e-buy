<?php

namespace App\Domain\Article\Event;

use App\Domain\Article\Article;
use Symfony\Contracts\EventDispatcher\Event;

class ArticleUpdatedEvent extends Event{

    public function __construct(private readonly Article $article)
    {
    }

    /**
     * Retourne une entite article
     *
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

}