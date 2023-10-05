<?php

namespace App\Infrastructure\Search\Subscriber;

use App\Domain\Article\Event\ArticleUpdateEvent;
use App\Infrastructure\Search\IndexerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexerSubscriber implements EventSubscriberInterface{


    public function __construct(private IndexerInterface $indexer,private NormalizerInterface $normalizer)
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
        $data = $this->normalizer->normalize($event->getArticle(), 'search');
        $this->indexer->index($data);
    }

}