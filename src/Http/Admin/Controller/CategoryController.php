<?php

namespace App\Http\Admin\Controller;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepository;
use App\Domain\Article\Event\ArticleCreatedEvent;
use App\Domain\Article\Event\ArticleDeletedEventEvent;
use App\Domain\Article\Event\ArticleUpdatedEvent;
use App\Domain\Category;
use App\Domain\CategoryRepository;
use App\Form\ArticleType;
use App\Http\Admin\Data\ArticleCrudData;
use App\Http\Admin\Data\CategoryCrudData;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/", "admin.")
 */
class CategoryController extends CrudController
{

    protected string $templatePath = 'category';
    protected string $entity = Category::class;
    protected string $routePrefix = 'admin.category';


    /**
     * @Route(path="category/edit/{id}", name="category.edit")
     */
    public function edit(
        Category $category,
    ): Response {
        $data = CategoryCrudData::makeFromPost($category);
        $data->entity = $category;

        return $this->crudEdit($data);
    }

    /**
     * @Route("category/delete/{id}", "category.delete", methods={"DELETE"})
     */
    public function delete(
        Article $article
    ) {
        /**
         * Creation des formulaires
         */
        //return $this->redirectToRoute('admin.article');
    }

    /**
     * @Route(path="category", name="category.index", methods="GET")
     */
    public function show(Request $request): Response
    {
        /** @var string|null $q */
        $q = $request->get('q');
        // Si une recherche se lance
        if($q !== null) {
            $query = $request->get('q');
            /** @var QueryBuilder $query */
            $query = $this->getRepository()
                ->createQueryBuilder('row')
                ->orWhere(
                    $this->getRepository()->createQueryBuilder('r')
                        ->expr()->like('row.name', ':search')
                )
                ->orWhere(
                    $this->getRepository()->createQueryBuilder('r')
                        ->expr()->like('row.description', ':search')
                )
                ->setParameter('search', '%' . $query . '%');

            return $this->crudIndex($query,12,['q'=>$q]);
        }

        /** @var Query $query */
        $query = $this->getRepository()
            ->createQueryBuilder('c')
            ->orderBy('c.created_at','DESC');

        return $this->crudIndex($query);
    }

    /**
     * @Route("category/new", "category.new")
     * @return Response
     */
    public function newCategory():Response{
        $category = new Category();
        $categoryData = new CategoryCrudData();
        $categoryData->entity = $category;

        return $this->crudNew($categoryData);
    }
}
