<?php
namespace App\Http\Controller;

use App\Domain\Cart\service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController{

    public string $entity = '';

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $manager
     * @param RequestStack $requestStack
     * @param CartService $cartService
     */
    public function __construct(
        public readonly EventDispatcherInterface $dispatcher,
        public readonly PaginatorInterface $paginator,
        public readonly EntityManagerInterface $manager,
        public readonly RequestStack $requestStack,
        public readonly CartService $cartService,
    )
    {
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters = [
            ...$parameters,
            'cart' => $this->cartService->getAllArticleCount()
        ];
        return parent::render($view, $parameters, $response);
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->manager->getRepository($this->entity);
    }
}