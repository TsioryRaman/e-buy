<?php

namespace App\Http\Admin\Controller;

use App\Domain\Article\Article;
use App\Domain\Article\Event\ArticleCreatedEvent;
use App\Domain\Article\Event\ArticleDeletedEventEvent;
use App\Domain\Article\Event\ArticleUpdatedEvent;
use App\Http\Admin\Data\ArticleCrudData;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/", name="admin.")
 */
class ArticleController extends CrudController
{

    protected string $templatePath = 'article';
    protected string $entity = Article::class;
    protected string $routePrefix = 'admin.article';
    protected array $events = [
        'update' => ArticleUpdatedEvent::class,
        'delete' => ArticleDeletedEventEvent::class,
        'create' => ArticleCreatedEvent::class
    ];

    /**
     * @Route(path="article/edit/{id}", name="article.edit")
     */
    public function edit(
        Article $article,
    ): Response {
        $data = ArticleCrudData::makeFromPost($article);
        $data->entity = $article;
        return $this->crudEdit($data);
    }

    /**
     * @Route("article/delete/{id}", "article.delete", methods="POST")
     */
    public function delete(Article $article): Response {
        $data = new ArticleCrudData();
        $data->entity = $article;
        return $this->crudDelete($data);
    }

    /**
     * @Route(path="article", name="article.index", methods="GET")
     */
    public function show(Request $request): Response
    {
        $q = $request->get('q');
        if($q){
            $query = $request->get('q');
            /** @var QueryBuilder $query */
            $query = $this->getRepository()
                ->createQueryBuilder('row')
                ->where(
                    $this->getRepository()->createQueryBuilder('r')
                        ->expr()->like('row.name',':search')
                )
                ->setParameter('search','%' . $query . '%');

            return $this->crudIndex($query,12,['q'=>$q]);
        }
        /** @var QueryBuilder $query */
        $query = $this->getRepository()->findLatestCrud();

        return $this->crudIndex($query);
    }

    /**
     * @Route("article/new", name="article.new")
     */
    public function newArticle(): Response{
        $article = new Article();
        $articleData = new ArticleCrudData();
        $articleData->entity = $article;

        return $this->crudNew($articleData);
    }
}
