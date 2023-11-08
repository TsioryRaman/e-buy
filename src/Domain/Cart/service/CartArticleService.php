<?php

namespace App\Domain\Cart\service;

use App\Domain\Article\Article;
use App\Domain\Auth\User;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartArticle;
use App\Domain\Cart\CartData;
use Doctrine\ORM\EntityManagerInterface;

class CartArticleService
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
    )
    {
    }

    public function addArticleToCart(
        Article $article,
        CartData $cartData,
        int $oldQuantity,
        User $user
    ): Cart
    {
        $cart = $this->manager->getRepository(Cart::class)->findLastCartNotSubmitted();
        $cartArticle = null;
        if ($cart) {
            $cartArticle = $this->manager->getRepository(CartArticle::class)->findOneBy(
                [
                    'cart' => $cart->getId(),
                    'article' => $article->getId()
                ]
            );
        } else {
            $cart = new Cart();
            $cart->setUser($user);
        }

        if ($cartArticle) {
            $cartArticle->setQuantity($cartData->getQuantity());
        } else {
            $cartArticle = (new CartArticle())
                ->setCart($cart)
                ->setArticle($article)
                ->setQuantity($cartData->getQuantity());
        }
        $this->manager->persist($cartArticle);
        $article->setQuantity($article->getQuantity() + $oldQuantity - $cartData->getQuantity());
        $this->manager->flush();

        return $cart;
    }

    public function getCurrentUserCart(User $user): Cart|null
    {
        return $this->manager->getRepository(Cart::class)->findArticleNotPurchased($user->getId());
    }
}