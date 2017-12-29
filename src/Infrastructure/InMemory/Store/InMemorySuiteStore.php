<?php

namespace App\Infrastructure\InMemory\Store;

use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;
use App\Domain\Query\PagerContext;

class InMemorySuiteStore implements SuiteStore
{
    private $suites = [];

    public function store(string $id, array $data): void
    {
        $this->suites[$id] = $data;
    }

    public function forSuiteUuid(string $uuid): array
    {
        return $this->suites[$uuid];
    }

    public function forProject(PagerContext $context, ProjectName $project): ResultSet
    {
        return ResultSet::create(array_filter($this->suites, function (array $data) use ($project) {
            return $data['project-name'] === $project->name() &&
                $data['project-namespace'] === $project->namespace();
        }));
    }

    public function all(PagerContext $context): ResultSet
    {
        return ResultSet::create($this->suites);
    }

    public function forNamespace(PagerContext $context, string $namespace): ResultSet
    {
        return ResultSet::create(array_filter($this->suites, function (array $data) use ($project) {
            return $data['project-namespace'] === $project->namespace();
        }));
    }
}
