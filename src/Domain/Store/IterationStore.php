<?php

namespace App\Domain\Store;

interface IterationStore
{
    public function store(string $id, array $data): void;

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant);
}
