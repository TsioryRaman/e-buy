<?php

namespace App\Domain\Cart\service;

use App\Domain\Article\repository\ArticleRepository;
use App\Domain\Cart\ArticleMoreThanQuantityException;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartArticle;
use App\Domain\Cart\CartData;
use App\Infrastructure\Session\SessionService;
use App\Repository\Domain\Cart\CartArticleRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CartService
{
    public function __construct(
        private readonly SessionService           $session,
        private readonly ArticleRepository        $articleRepository,
        private readonly EntityManagerInterface   $manager,
        private readonly NormalizerInterface      $normalizer,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function addArticle(CartData $cart): void
    {
        $this->manager->getConnection()->beginTransaction();
        $carts = $this->session->get('cart');
        $article = $this->articleRepository->find($cart->getId());
        if (!$article || $article->getQuantity() < $cart->getQuantity()) {
            throw new ArticleMoreThanQuantityException("L'article demandee ne possede pas autant de quantite", Response::HTTP_BAD_REQUEST);
        }
        if ((array_key_exists($cart->getId(), $carts) && $cart->getQuantity() === 0)) {
            // supprimer dans le panier
            unset($carts[$cart->getId()]);
//            $cartArticle = $this->articleRepository->find($cart->getId())->getCartArticles();
//            $cartArticle->re
        } else if (!array_key_exists($cart->getId(), $carts)) {
            $_cart = new Cart();
            $cartArticle = new CartArticle();
            $cartArticle->setArticle($this->articleRepository->find($cart->getId()));
            $cartArticle->setQuantity($cart->getQuantity());
            $cartArticle->setCart($_cart);
            $carts[$cart->getId()] = $cart->getQuantity();
        } else {
            $carts[$cart->getId()] = $cart->getQuantity();
        }
        $this->manager->flush();
        $this->manager->getConnection()->commit();

        $this->session->add('cart', $carts);
    }


    /**
     * @throws ArticleMoreThanQuantityException
     * @throws Exception
     */
    public function removeArticle(CartData $cart): void
    {
        $this->manager->getConnection()->beginTransaction();
        $carts = $this->session->get('cart');
        $article = $this->articleRepository->find($cart->getId());
        if (!$article) {
            throw new ArticleMoreThanQuantityException("L'article demandee n'existe pas.", Response::HTTP_BAD_REQUEST);
        }

        $article->setQuantity($article->getQuantity() - $cart->getQuantity() + $carts[$cart->getId()]);
        $this->manager->flush();
        if ((array_key_exists($cart->getId(), $carts) && $cart->getQuantity() === 0) || $carts[$cart->getId()] <= 0) {
            unset($carts[$cart->getId()]);
        } else {
            $carts[$cart->getId()] = $cart->getQuantity();
        }
        $this->manager->getConnection()->commit();

        $this->session->add('cart', $carts ?? []);
    }

    /**
     * @return array
     * @throws ExceptionInterface
     */
    public function getArticles(): array
    {
        $carts = $this->session->get('cart');
        $articles = [];
//        $article = $this->articleRepository->findArticleWithId(array_keys($carts));
        foreach ($carts as $key => $quantity) {
            $article = $this->articleRepository->find($key);
            $articles[] = $this->normalizer->normalize([$article, $quantity], 'cart');
        }

        return $articles;
    }

    public function getCurrentArticle(CartData $cart): array|null
    {
        $article = $this->articleRepository->find($cart->getId());
        if ($article) {
            $data = [$article, $cart->getQuantity()];
            return $this->normalizer->normalize($data, 'cart');
        }

        return null;
    }

    public function getAllArticleCount(): int
    {
        $result = 0;
        $cart = $this->session->get('cart');

        foreach ($cart as $key => $quantity) {
            $result += $quantity;
        }

        return $result;
    }


    private function calcAddQuantityToCart($new_quantity, $old_quantity): int
    {
        return $new_quantity + $old_quantity;
    }

}