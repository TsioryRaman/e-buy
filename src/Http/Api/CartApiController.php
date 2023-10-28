<?php

namespace App\Http\Api;

use App\Domain\Cart\ArticleMoreThanQuantityException;
use App\Domain\Cart\CartData;
use App\Domain\Cart\service\CartService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[IsGranted('ROLE_USER')]
#[Route(path: '/api')]
class CartApiController extends AbstractController
{
    public function __construct(
        private readonly CartService $service,
        private readonly DenormalizerInterface $normalizer
    ){}

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    #[Route(path: '/cart',name: 'cart.add',methods: ["POST"])]
    public function add(Request $request):Response
    {
        $content = json_decode($request->getContent(),true);
        /** @var CartData $cart */
        $cart = $this->normalizer->denormalize($content,CartData::class);
        try {
            $this->service->addArticle($cart);
            return $this->json($this->service->getCurrentArticle($cart),Response::HTTP_OK);
        }catch (ArticleMoreThanQuantityException $e)
        {
            return $this->json($e,Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    #[Route(path: '/cart',name: 'cart.remove',methods: ["DELETE"])]
    public function remove(Request $request):Response
    {
        $content = json_decode($request->getContent(),true);
        /** @var CartData $cart */
        $cart = $this->normalizer->denormalize($content,CartData::class);
        try {
            $this->service->removeArticle($cart);
            return $this->json($this->service->getArticles(),Response::HTTP_OK);
        }catch (ArticleMoreThanQuantityException $e)
        {
            return $this->json($e,Response::HTTP_BAD_REQUEST);
        }
    }

}