<?php

namespace App\Domain\Article\Subscriber;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleView;
use App\Domain\Article\Event\ArticleViewEvent;
use App\Domain\Auth\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleViewEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleViewEvent::class => 'viewArticle'
        ];
    }

    public function viewArticle(object $event): void
    {
        /** @var Article $article */
        /** @var User $user */
        [$article, $user] = $event->getArticleWithUser();
        if ($user && $user->getRoles() === ['ROLE_USER']) {
            $articleView = new ArticleView();
            $articleView->setViewBy($user);
            $articleView->setArticle($article);
            $this->manager->persist($articleView);
            $this->manager->flush();
        }
    }
}