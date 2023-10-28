<?php

namespace App\Domain\Article\manager;

use App\Domain\Article\Article;
use App\Domain\Article\repository\ArticleRepository;
use App\Domain\Auth\User;
use Doctrine\ORM\EntityManagerInterface;

class ArticleManager
{
    public function __construct(private readonly EntityManagerInterface $manager,private readonly ArticleRepository $repository)
    {
    }

    public function likeOrDislike(int $article_id,User $user = null):bool
    {
        /** @var Article|null $article */
        $article = $this->getArticle($article_id);
        if($article && $user && $article->getLikeBy()->contains($user))
        {
            $article->removeLikeBy($user);
        }else if($user && $article)
        {
            $article->addLikeBy($user);
        }
        $this->manager->flush();

        return $article->getLikeBy()->contains($user);
    }

    private function getArticle(int $id): Article|null
    {
        return $this->repository->find($id);
    }

}