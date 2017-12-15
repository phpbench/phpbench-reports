<?php

namespace App\Domain\Store;

interface IterationStore
{
    public function store(string $id, array $data): void;
}
