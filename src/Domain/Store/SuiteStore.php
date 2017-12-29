<?php

namespace App\Domain\Store;

use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;

interface SuiteStore
{
    public function store(string $id, array $data): void;

    public function forSuiteUuid(string $uuid): array;

    public function forProject(ProjectName $project): ResultSet;

    public function all(): ResultSet;

    public function forNamespace(string $namespace): ResultSet;
}
