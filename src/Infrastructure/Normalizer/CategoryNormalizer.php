<?php

namespace App\Infrastructure\Normalizer;

use App\Domain\Article\Article;
use App\Domain\Category;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryNormalizer implements NormalizerInterface{


    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Category && $format === 'search';
    }

    /**
     * Normaliser les donnees des articles
     *
     * @param Category $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        /**
         * var Category $object
         */
        if(!$object instanceof Category){
            throw new InvalidArgumentException('Type innatendu');
        }

        return [
            'id' => (string)$object->getId(),
            'name' => $object->getName(),
            'brand' => '',
            'description' => $object->getDescription(),
            'category' => '',
            'address' => '',
            'store' => [],
            'type' => 'category',
            'created_at' => (string)$object->getCreatedAt()->getTimestamp()
        ];
    }

}