<?php

namespace App\Http\Admin\Controller;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepository;
use App\Domain\CategoryRepository;
use App\Form\ArticleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param ArticleRepository $articeRepository
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        private ArticleRepository $articeRepository,
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $manager
    ) {
    }

    /**
     * @Route(path="admin", name="admin.article", methods="GET")
     */
    public function index()
    {
        $articles = $this->articeRepository->findAll();
        return $this->render('admin/article/index.html.twig', ["articles" => $articles]);
    }

    /**
     * @Route(path="admin/article/edit/{id}", name="admin.article.edit")
     */
    public function edit(
        Article $article,
        Request $request
    ) {
        
        /**
         * Creation des formulaires
         * @var $form FormInterface
         */
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        // Soumission des formulaires et validation
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            return $this->redirectToRoute('admin.article');
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'form' => $form
        ]);
    }
}
