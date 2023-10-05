<?php

namespace App\Infrastructure\Search\Typesense;

use App\Infrastructure\Search\SearchInterface;

class TypesenseSearch implements SearchInterface{


    public function __construct(private TypesenseClient $client)
    {
    }

    public function search(string $q, $types = []): array
    {
        $q = urlencode($q);
        // http://typesense:8108/collections/content/documents/search?q=ts&query_by=name
        $data = $this->client->get("collections/content/documents/search?q={$q}&query_by=name,category,description,address&limit=20");
        return $data;
    }

}