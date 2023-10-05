<?php

namespace App\Infrastructure\Search;

interface SearchInterface {

    public function search(string $q, $types = []): array;

}