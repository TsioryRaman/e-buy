<?php

namespace App\Http\Api;

use App\Domain\Article\Article;
use App\Domain\Article\manager\ArticleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[IsGranted('ROLE_USER')]
class ArticleApiController extends AbstractController
{
    public function __construct(private readonly ArticleManager $articleManager)
    {
    }

    /**
     */
    #[Route(path: 'article/like', name: 'article.like', methods: ['POST'])]
    public function likeOrDislike(
        Request $request): Response
    {
        $data = json_decode((string)$request->getContent(), true, 512);
        $articleLiked = $this->articleManager->likeOrDislike($data['article_id'], $this->getUser());

        return $this->json($articleLiked,Response::HTTP_OK);
    }

}