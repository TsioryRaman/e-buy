<?php

namespace App\Infrastructure\Normalizer;

use App\Domain\Article\Article;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleNormalizer implements NormalizerInterface{


    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Article && $format === 'search';
    }

    /**
     * Normaliser les donnees des articles
     *
     * @param Article $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        /**
         * var Article $object 
         */
        if(!$object instanceof Article){
            throw new InvalidArgumentException('Type innatendu');
        }

        return [
            'id' => (string)$object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'category' => $object->getCategory()->getName(),
            'address' => $object->getAddress(),
            'store' => array_map(fn($s) => $s->getName() ,$object->getStores()->toArray()),
            'type' => 'article',
            'created_at' => (string)$object->getCreatedAt()->getTimestamp()
        ];
    }

}