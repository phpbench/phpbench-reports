<?php

namespace App\Domain\Store;

use App\Domain\Query\ResultSet;

interface IterationStore
{
    public function storeMany(array $documents): void;

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant): ResultSet;
}
