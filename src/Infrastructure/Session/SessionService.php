<?php

namespace App\Infrastructure\Session;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{

    public SessionInterface $session;
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(string $name, $value): void
    {
        $this->session->set($name, $value);
    }

    public function get(string $key, $default = [])
    {
        return $this->session->get($key, $default);
    }
    public function remove(string $key)
    {
        return $this->session->remove($key);
    }

}