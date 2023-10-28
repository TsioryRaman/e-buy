<?php

namespace App\Infrastructure\Normalizer;

use App\Domain\Article\Article;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleNormalizerCart implements NormalizerInterface
{


    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
            return gettype($data) === "array" && array_key_exists(0, $data) && $data[0] instanceof Article && $format === 'cart';
    }

    /**
     * Normaliser les donnees des articles pour le cart
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
        if (!$object[0] instanceof Article) {
            throw new InvalidArgumentException('Type innatendu');
        }

        return [
            'id' => $object[0]->getId(),
            'name' => $object[0]->getName(),
            'brand' => $object[0]->getBrand(),
            'description' => $object[0]->getDescription(),
            'category' => $object[0]->getCategory()->getName(),
            'address' => $object[0]->getAddress(),
            'store' => array_map(fn($s) => $s->getName(), $object[0]->getStores()->toArray()),
            'created_at' => $object[0]->getCreatedAt(),
            'cart_quantity' => $object[1],
            'quantity' => $object[0]->getQuantity(),
            'price' => $object[0]->getPrice() * $object[1],
            'price_unity' => $object[0]->getPrice()
        ];
    }

}