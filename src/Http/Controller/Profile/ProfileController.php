<?php

namespace App\Http\Controller\Profile;

use App\Domain\Auth\service\UserService;
use App\Domain\Auth\User;
use App\Domain\Cart\service\CartService;
use App\Domain\profile\Dto\ProfileUpdateRequestData;
use App\Form\user\AvatarFormType;
use App\Form\user\PasswordFormType;
use App\Http\Controller\BaseController;
use App\Repository\Domain\Cart\CartArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[IsGranted('ROLE_USER')]
#[Route(path: '/user/', name: 'user.')]
class ProfileController extends BaseController
{

    #[Route(path: 'setting/profile/{id<\d+>}', name: 'setting')]
    public function setting(User $user,Request $request,UserService $service,TokenStorageInterface $tokenStorage):Response
    {
        $dataUser = new ProfileUpdateRequestData($user);
        $formAvatar = $this->createForm(AvatarFormType::class, $dataUser);
        $formPassword = $this->createForm(PasswordFormType::class, $dataUser);
        $formAvatar->handleRequest($request);
        $formPassword->handleRequest($request);
        if($formAvatar->isSubmitted() && $formAvatar->isValid())
        {
            $dataUser->hydrate();
        }
        if(
            $formPassword->isSubmitted() &&
            $formPassword->isValid()
        )
        {
            $newPassword = $service->hashPassword($dataUser->password,$dataUser->user);
            $dataUser->user->setPassword($newPassword);
            $this->addFlash('success','Votre mot de passe a bien ete modifie');
        }
        $this->manager->flush();

        return $this->render('user/index.html.twig',
            [
                'formAvatar' => $formAvatar->createView(),
                'formPassword' => $formPassword->createView()
            ]
        );
    }

    private function getPasswordEncoder():PasswordHasherInterface
    {
        return $this->container->get('security.password_hasher');
    }
}