<?php

namespace App\Domain\Store;

use App\Domain\Project\ProjectName;

interface VariantStore
{
    public function storeMany(array $documents): void;

    public function forSuiteUuid(string $uuid): array;

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): array;

    public function forProjectAndClass(ProjectName $projectName, string $class): array;
}
