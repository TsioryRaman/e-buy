<?php

namespace App\Http\Admin\Data;

interface CrudDataInterface
{
    public function getEntity(): object;

    public function getForm(): string;

    public function hydrate(): void;
    public function hydrateNewEntity(): void;
}