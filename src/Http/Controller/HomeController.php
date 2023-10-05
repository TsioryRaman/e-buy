<?php

namespace App\Http\Controller;

use App\Domain\Article\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     * 
     */
    public function index(
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $articles = $paginator
                        ->paginate(
                            $articleRepository->findLatest(),
                            $request->query->getInt('page',1),
                            10);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "articles" => $articles
        ]);
    }
}
