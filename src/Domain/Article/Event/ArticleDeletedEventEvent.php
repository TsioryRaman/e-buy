<?php

namespace App\Domain\Article\Event;

use App\Domain\Article\Article;
use Symfony\Contracts\EventDispatcher\Event;

class ArticleDeletedEventEvent extends Event{

    public function __construct(private readonly Article $article)
    {
    }

    /**
     *
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

}