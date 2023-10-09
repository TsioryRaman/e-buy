<?php

namespace App\Infrastructure\Search\Typesense;

use App\Infrastructure\Search\IndexerInterface;
use Symfony\Component\HttpFoundation\Response;

class TypesenseIndexer implements IndexerInterface
{

    private const COLECTION = [
        'name' => 'content',
        'fields' => [
            ['name' => 'name', 'type' => 'string'],
            ["name" => 'description', 'type' => 'string'],
            ["name" => 'brand', 'type' => 'string'],
            ['name' => 'category', 'type' => 'string'],
            ['name' => 'address', 'type' => 'string'],
            ['name' => 'store', 'type' => 'string[]'],
            ['name' => 'created_at', "type" => 'string'],
            ['name' => 'type', 'type' => 'string', 'facet' => true]
        ]
    ];

    /**
     * @param TypesenseClient $client
     */
    public function __construct(private readonly TypesenseClient $client)
    {
    }

    /**
     * Indexation de chaque article pour la recherche
     */
    public function index(array $data): void
    {
        try {
            // Teste et met a jour si la valeur est deja dans la collection
            $this->client->patch("collections/content/documents/{$data['id']}", $data);
        } catch (TypesenseException $exception) {
            // Si le status est 404 et que la collection n'existe pas encore
            if ($exception->status === Response::HTTP_NOT_FOUND && 'Not Found' === $exception->message) {
                // Si la collection n'est pas encore defini, on reorganise
                $this->client->post("collections", self::COLECTION);
                $this->client->post("collections/content/documents", $data);
                // Si le status est 404 mais que la collection existe deja
            } elseif ($exception->status === Response::HTTP_NOT_FOUND) {
                $this->client->post("collections/content/documents", $data);
                // Sinon, on leve une exception :(
            } else {
                throw $exception;
            }
        }
    }
}
