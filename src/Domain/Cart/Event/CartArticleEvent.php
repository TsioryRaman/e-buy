<?php

namespace App\Domain\Cart\Event;

use App\Domain\Cart\CartArticle;

class CartArticleEvent
{
    public function __construct(
        private readonly CartArticle $cartArticle
    )
    {
    }

    public function getCartArticle():CartArticle
    {
        return $this->cartArticle;
    }


}