<?php

namespace App\Domain\Article\Event;

use App\Domain\Article\Article;
use App\Domain\Auth\User;
use Symfony\Contracts\EventDispatcher\Event;

class ArticleViewEvent extends Event
{
    public function __construct(private readonly Article $article,private readonly ?User $user = null)
    {
    }

    /**
     * Retourne une entite article
     *
     * @return array
     */
    public function getArticleWithUser(): array
    {
        return [$this->article,$this->user];
    }
}