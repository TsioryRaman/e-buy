<?php

namespace App\Infrastructure\Search\Typesense;

use RuntimeException;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class TypesenseException extends RuntimeException{

    public $status;

    public $message;

    public function __construct(ResponseInterface $response)
    {
        $this->message = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR)['message'] ?? '';
        $this->status = $response->getStatusCode();
    }

}