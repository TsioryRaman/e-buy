<?php

namespace App\Http\Controller\article;

use App\Domain\Article\Article;
use App\Domain\Article\repository\ArticleRepository;
use App\Http\Controller\BaseController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    #[Route(path: "/", name: "home.index")]
    public function index(
        ArticleRepository  $articleRepository,
        PaginatorInterface $paginator,
        Request            $request): Response
    {
        /** @var Article[] $articles */
        $articles = $articleRepository->findLatest()->getQuery()->getResult();
        $articles = $paginator
            ->paginate(
                $articleRepository->findLatest(),
                $request->query->getInt('page', 1),
                10);
        return $this->render('home/index.html.twig', [
            "articles" => $articles
        ]);
    }
}
