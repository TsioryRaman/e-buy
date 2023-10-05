<?php

namespace App\Infrastructure\Search\EventSubscriber;

use App\Domain\Article\Event\ArticleUpdateEvent;
use App\Infrastructure\Search\IndexerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IndexerSubscriber implements EventSubscriberInterface{


    public function __construct(private IndexerInterface $indexer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleUpdateEvent::class => ['indexContent']
        ];
}

    public function indexContent(ArticleUpdateEvent $event)
    {
        dd($event);
    }

}