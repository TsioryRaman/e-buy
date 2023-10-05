<?php

namespace App\Http\Admin\Controller;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepository;
use App\Domain\Article\Event\ArticleUpdateEvent;
use App\Domain\CategoryRepository;
use App\Form\ArticleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
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
        private ArticleRepository $articleRepository,
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $manager,
        private PaginatorInterface $paginator,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @Route(path="admin", name="admin.article", methods="GET")
     */
    public function show(Request $request)
    {
        $articles = $this->paginator
                        ->paginate(
                            $this->articleRepository->findLatest(),
                            $request->query->getInt('page',1),
                        12);
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
            $this->dispatcher->dispatch(new ArticleUpdateEvent($article),ArticleUpdateEvent::class);
            return $this->redirectToRoute('admin.article');
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("admin/article/delete/{id}", "admin.article.delete", methods={"DELETE"})
     */
    public function delete(
        Article $article
    ) {
        /**
         * Creation des formulaires
         * @var $form FormInterface
         */
        dd("Suppresion");
        return $this->redirectToRoute('admin.article');
    }
}
