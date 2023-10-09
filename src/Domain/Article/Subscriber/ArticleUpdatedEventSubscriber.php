<?php

namespace App\Domain\Article\Subscriber;

use App\Domain\Article\Article;
use App\Domain\Article\Event\ArticleUpdatedEvent;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ArticleUpdatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CacheManager $manager,private readonly UploaderHelper $helper)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleUpdatedEvent::class => 'updateArticle'
        ];
    }

    public function updateArticle(object $event): void
    {
        /** @var Article $article */
        $article = $event->getOldArticle();
        foreach ($article->getAttachment() as $a)
        {
            $this->manager->remove($this->helper->asset($a,'imageFile'));
        }

    }
}