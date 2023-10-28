<?php

namespace App\Http\Controller;

use App\Domain\Article\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CartController extends BaseController
{
    public string $entity = Article::class;
    #[Route('/cart', name: 'cart.show')]
    public function show()
    {
        try {
            $articles = $this->cartService->getArticles();
        } catch (ExceptionInterface $e) {
            $articles = [];
        }
        return $this->render('cart/index.html.twig',[
            'articles' => $articles
        ]);
    }
}