<?php

namespace App\Infrastructure\Search;

interface IndexerInterface {

    public function index(object $entity): bool;

}