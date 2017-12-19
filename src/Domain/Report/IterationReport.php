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

    /**
     * @var IterationTabulator
     */
    private $tabulator;

    public function __construct(IterationStore $iterationStore, IterationTabulator $tabulator)
    {
        $this->iterationStore = $iterationStore;
        $this->tabulator = $tabulator;
    }

    public function iterationsForUuidClassSubjectAndVariant(
        string $uuid,
        string $class,
        string $subject,
        string $variant
    ): array {
        return $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant);
    }

    public function chartForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant)
    {
        return $this->tabulator->chart(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
    }

    public function histogramForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant)
    {
        return $this->tabulator->histogram(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
    }
}
