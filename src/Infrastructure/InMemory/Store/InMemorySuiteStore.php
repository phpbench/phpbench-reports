<?php

namespace App\Infrastructure\InMemory\Store;

use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;

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

    public function forUserId(string $uuid): array
    {
        return array_filter($this->suites, function (array $data) use ($uuid) {
            return $data['user-id'] === $uuid;
        });
    }

    public function forProject(ProjectName $project): array
    {
        return array_filter($this->suites, function (array $data) use ($project) {
            return $data['project-name'] === $project->name() &&
                $data['project-namespace'] === $project->namespace();
        });
    }

    public function all(): array
    {
        return $this->suites;
    }

    public function forNamespace(string $namespace)
    {
        return array_filter($this->suites, function (array $data) use ($project) {
            return $data['project-namespace'] === $project->namespace();
        });
    }
}
