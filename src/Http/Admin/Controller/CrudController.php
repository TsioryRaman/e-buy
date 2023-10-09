<?php

namespace App\Http\Admin\Controller;

use App\Domain\Article\Article;
use App\Http\Admin\Data\CrudDataInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CrudController extends AbstractController
{
    protected string $entity;
    protected string $templatePath = '';
    protected string $routePrefix = '';
    protected array $events = [
        'update' => null,
        'delete' => null,
        'create' => null
    ];
    protected bool $indexOnSave = true;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PaginatorInterface $paginator,
        private readonly EventDispatcherInterface $dispatcher,
        protected readonly RequestStack $requestStack
    ) {
    }

    public function crudIndex(QueryBuilder $query = null,int $limit = 12,array $extraParams = []):Response{
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $query = $query ?: $this->getRepository()
            ->createQueryBuilder("row")
            ->orderBy('row.createdAt','DESC');

        $rows = $this->paginator->paginate(
                                                $query->getQuery(),
                                                $request->query->getInt('page',1),
                                                $limit);
        return $this->render("admin/{$this->templatePath}/index.html.twig",[
           'rows' => $rows,
            ...$extraParams
        ]);
    }

    public function crudNew(CrudDataInterface $data): Response{
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $form = $this->createForm($data->getForm(),$data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data->hydrateNewEntity();
            $entity = $data->getEntity();
            $this->em->persist($entity);
            $this->em->flush();
            if($this->events['create'] ?? null) {
                $this->dispatcher->dispatch(new $this->events['create']($entity));
            }

            return $this->redirectAfterSave($entity);
        }

        return $this->render("admin/{$this->templatePath}/new.html.twig", [
            'form' => $form->createView(),
            'entity' => $data->getEntity()
        ]);
    }

    public function crudEdit(CrudDataInterface $data): Response{
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $form = $this->createForm($data->getForm(),$data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entity = $data->getEntity();
            $old = clone $entity;
            $data->hydrate();
            $this->em->persist($data->getEntity());
            $this->em->flush();
            if($this->events['update'] ?? null) {
                $this->dispatcher->dispatch(new $this->events['update']($data->getEntity(),$old));
            }

            return $this->redirectAfterSave($entity);
        }

        return $this->render("admin/{$this->templatePath}/edit.html.twig", [
            'form' => $form->createView(),
            'entity' => $data->getEntity()
        ]);
    }

    public function crudDelete(CrudDataInterface $data):Response {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $entity = $data->getEntity();
        $result = $this->isCsrfTokenValid('delete' . $entity->getId(),$request->get('_csrf_token'));
        if($request->get('_method') === Request::METHOD_DELETE && $result){
            $this->em->remove($entity);
            $this->em->flush();
        }

        return $this->redirectToRoute($this->routePrefix.'.index');

    }

    /**
     * @param object $entity
     * @return RedirectResponse
     */
    protected function redirectAfterSave(object $entity): RedirectResponse
    {
        if ($this->indexOnSave) {
            return $this->redirectToRoute($this->routePrefix.'.index');
        }

        return $this->redirectToRoute($this->routePrefix.'.edit', ['id' => $entity->getId()]);
    }

    public function getRepository(): EntityRepository
    {
        /* @var EntityRepository */
        return $this->em->getRepository($this->entity); /* @phpstan-ignore-line */
    }
}