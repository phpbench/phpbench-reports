<?php

namespace App\Domain\Report;

use App\Domain\Store\IterationStore;
use App\Domain\Report\Tabulator\IterationTabulator;

class IterationReport
{
    /**
     * @var IterationStore
     */
    private $iterationStore;

    public function __construct(IterationStore $iterationStore)
    {
        $this->iterationStore = $iterationStore;
    }

    public function iterationsForUuidClassSubjectAndVariant(
        string $uuid,
        string $class,
        string $subject,
        string $variant
    ): array
    {
        return IterationTabulator::iterations(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
    }

    public function chartForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant)
    {
        return IterationTabulator::chart(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
    }

    public function histogramForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant)
    {
        return IterationTabulator::histogram(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );

    }
}
