<?php

namespace App\Http\Controller\article;

use App\Domain\Article\Article;
use App\Domain\Article\Event\ArticleViewEvent;
use App\Http\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends BaseController
{

    #[Route('article/{id}','article.show')]
    public function show(Article $article): Response
    {
        $this->dispatcher->dispatch(new ArticleViewEvent($article,$this->getUser()));
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }


}
