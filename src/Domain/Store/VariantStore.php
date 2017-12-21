<?php

namespace App\Domain\Store;

interface VariantStore
{
    public function storeMany(array $documents): void;

    public function forSuiteUuid(string $uuid): array;

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): array;
}
