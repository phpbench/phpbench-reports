<?php

namespace App\Domain\Store;

use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;

interface VariantStore
{
    public function storeMany(array $documents): void;

    public function forSuiteUuid(string $uuid): ResultSet;

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): ResultSet;

    public function forProjectAndClass(ProjectName $projectName, string $class): ResultSet;
}
