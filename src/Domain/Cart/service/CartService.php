<?php

namespace App\Domain\Cart\service;

use App\Domain\Article\Article;
use App\Domain\Article\repository\ArticleRepository;
use App\Domain\Auth\User;
use App\Domain\Cart\ArticleMoreThanQuantityException;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartArticle;
use App\Domain\Cart\CartData;
use App\Infrastructure\Session\SessionService;
use App\Repository\Domain\Cart\CartArticleRepository;
use App\Repository\Domain\Cart\CartRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CartService
{
    public function __construct(
        private readonly SessionService         $session,
        private readonly EntityManagerInterface $manager,
        private readonly NormalizerInterface    $normalizer,
        private readonly CartArticleService     $cartArticleService
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function addArticle(CartData $cart, User $user): void
    {
        $this->manager->getConnection()->beginTransaction();
        $carts = $this->session->get('cart');
        $article = $this->manager->getRepository(Article::class)->find($cart->getId());
        if (!$article || $article->getQuantity() < $cart->getQuantity()) {
            throw new ArticleMoreThanQuantityException("L'article demandee ne possede pas autant de quantite", Response::HTTP_BAD_REQUEST);
        }
        $this->cartArticleService
            ->addArticleToCart(
                $article, $cart,
                $this->getQuantityIfExist($cart->getId()), $user
            );

        if ((array_key_exists($cart->getId(), $carts) && $cart->getQuantity() === 0)) {
            unset($carts[$cart->getId()]);
        } else if (!array_key_exists($cart->getId(), $carts)) {
            $carts[$cart->getId()] = $cart->getQuantity();
        } else {
            $carts[$cart->getId()] = $cart->getQuantity();
        }
        $this->manager->getConnection()->commit();
        $this->session->add('cart', $carts);
    }

    private function getQuantityIfExist(int $id)
    {
        $carts = $this->session->get('cart');

        if (array_key_exists($id, $carts)) {
            return $carts[$id];
        }

        return 0;
    }

    public function deleteCartFromSession(): void
    {
        $this->session->remove('cart');
    }

    /**
     * @throws ArticleMoreThanQuantityException
     * @throws Exception
     */
    public function removeArticle(CartData $cart): void
    {
        $this->manager->getConnection()->beginTransaction();
        $carts = $this->session->get('cart');
        $article = $this->manager->getRepository(Article::class)->find($cart->getId());
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

    public function submitCartUser(User $user): void
    {
        /** @var Cart $cart */
        $cart = $this->manager->getRepository(Cart::class)->findArticleNotPurchased($user->getId());
        $cart->setSubmitted(true)
            ->setUpdatedAt(new \DateTimeImmutable('now'));
        $this->manager->flush();
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
            $article = $this->manager->getRepository(Article::class)->find($key);
            $articles[] = $this->normalizer->normalize([$article, $quantity], 'cart');
        }

        return $articles;
    }

    public function getCurrentArticle(CartData $cart): array|null
    {
        $article = $this->manager->getRepository(Article::class)->find($cart->getId());
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