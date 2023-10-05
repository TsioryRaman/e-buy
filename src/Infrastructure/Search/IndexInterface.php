<?php

namespace App\Infrastructure\Search;

interface IndexerInterface {

    public function index(array $data);

}