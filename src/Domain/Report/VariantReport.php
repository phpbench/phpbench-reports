<?php

namespace App\Domain\Report;

use App\Domain\Store\VariantStore;
use App\Domain\Report\Tabulator\VariantTabulator;

class VariantReport
{
    /**
     * @var VariantStore
     */
    private $variantStore;

    /**
     * @var VariantTabulator
     */
    private $tabulator;

    public function __construct(
        VariantStore $variantStore,
        VariantTabulator $tabulator
    ) {
        $this->variantStore = $variantStore;
        $this->tabulator = $tabulator;
    }

    public function aggregatesForUuid(string $uuid): array
    {
        return $this->tabulator->aggregate($this->variantStore->forSuiteUuid($uuid));
    }

    public function chartForUuid(string $uuid): array
    {
        return $this->tabulator->chart(
            $this->variantStore->forSuiteUuid($uuid)
        );
    }

    public function aggregatesForUuidAndClass(string $uuid, string $class)
    {
        return $this->tabulator->aggregate(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
    }

    public function chartForUuidAndClass(string $uuid, string $class): array
    {
        return $this->tabulator->chart(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
    }
}
