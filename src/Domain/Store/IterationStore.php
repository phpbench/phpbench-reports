<?php

namespace App\Domain\Store;

interface IterationStore
{
    public function storeMany(array $documents): void;

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant);
}
