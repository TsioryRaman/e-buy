<?php

namespace App\Http\Api;

use App\Domain\Auth\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user')]
#[IsGranted('ROLE_USER')]
class UserApiController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    #[Route(path: '/theme',methods: ['POST'])]
    public function switchTheme(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        if(array_key_exists('theme',$data))
        {
            /** @var User $user */
            $user = $this->getUser();
            $user->setTheme($data['theme']);
            $this->manager->flush();

            return $this->json([],200);
        }

        return $this->json(['error'=>"Erreur lors de l'enregistrement du theme"], Response::HTTP_BAD_REQUEST);

    }
}