<?php

namespace App\Http\Controller;

use App\Domain\Auth\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager)
    {
    }

    /**
     * @Route(path="login", name="app_login", methods={"GET","POST"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if($this->getUser())
        {
            return $this->redirectToRoute('home.index');
        }

        return $this->render('auth/login.html.twig', [
            'controller_name' => 'UserController',
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route(path="register", name="user.register", methods={"GET","POST"})
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return void
     */
    public function register(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $encoder
    ) {
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        $user = new User();
        $errors = [];
        $messageError = [];

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $form->has('password') ? $encoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
                    : ''
            );
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute('app_login');
        } elseif ($form->isSubmitted()) {


            /**
             * Symfony\Component\Validator\ConstraintViolationList $errors
             */
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $messageError[$error->getPropertyPath()] = $error->getMessage();
                }
            }
        }

        // dd($form->getErrors());
        return $this->render('auth/register.html.twig', [
            "form" => $form->createView(),
            "error" => $messageError
        ]);
    }
}
