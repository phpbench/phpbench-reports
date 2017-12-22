<?php

namespace App\Infrastructure\InMemory\Store;

use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;
use App\Domain\Store\VariantStore;
use App\Domain\Store\IterationStore;

class InMemoryIterationStore implements IterationStore
{
    private $iterations = [];

    public function storeMany(array $documents): void
    {
        foreach ($documents as $id => $document) {
            $this->iterations[$id] = $document;
        }
    }

    public function forSuiteUuidBenchmarkSubjectAndVariant(
        string $uuid,
        string $class,
        string $subject,
        string $variant
    )
    {
        return array_filter($this->iterations, function (array $data) use ($uuid, $class, $subject, $variant) {
            return $data['suite-uuid'] === $uuid && 
                $data['benchmark-class'] === $class &&
                $data['subject-name'] === $subject &&
                $data['variant-index'] == $variant;
        });
    }

}
