<?php

namespace App\Infrastructure\InMemory\Store;

use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;
use App\Domain\Store\VariantStore;

class InMemoryVariantStore implements VariantStore
{
    private $variants = [];

    public function storeMany(array $documents): void
    {
        foreach ($documents as $id => $document) {
            $this->variants[$id] = $document;
        }
    }

    public function forSuiteUuid(string $uuid): array
    {
        return array_filter($this->variants, function (array $data) use ($uuid) {
            return $data['suite-uuid'] === $uuid;
        });
    }

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): array
    {
        return array_filter($this->variants, function (array $data) use ($uuid, $class) {
            return $data['suite-uuid'] === $uuid && 
                $data['benchmark-class'] === $class;
        });
    }

    public function forProjectAndClass(ProjectName $projectName, string $class): array
    {
        return array_filter($this->variants, function (array $data) use ($projectName, $class) {
            return $data['project-name'] === $projectName->name() && 
                $data['project-namespace'] === $projectName->namespace() && 
                $data['benchmark-class'] === $class;
        });
    }
}
