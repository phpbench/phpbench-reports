<?php

namespace App\Domain\Store;

use App\Domain\Project\ProjectName;

interface SuiteStore
{
    public function store(string $id, array $data): void;

    public function forSuiteUuid(string $uuid): array;

    public function forProject(ProjectName $project): array;

    public function all(): array;

    public function forNamespace(string $namespace);
}
