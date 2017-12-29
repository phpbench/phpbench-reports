<?php

namespace App\Domain\Store;

use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;
use App\Domain\Query\PagerContext;

interface SuiteStore
{
    public function store(string $id, array $data): void;

    public function forSuiteUuid(string $uuid): array;

    public function forProject(PagerContext $pager, ProjectName $project): ResultSet;

    public function all(PagerContext $pager): ResultSet;

    public function forNamespace(PagerContext $pager, string $namespace): ResultSet;
}
