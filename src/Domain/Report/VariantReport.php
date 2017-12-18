<?php

namespace App\Domain\Report;

use App\Domain\Store\SuiteStore;
use App\Domain\User\BenchUserRepository;
use App\Domain\Store\VariantStore;
use App\Domain\Report\Tabulator\VariantTabulator;

class VariantReport
{
    /**
     * @var VariantStore
     */
    private $variantStore;

    public function __construct(
        VariantStore $variantStore
    )
    {
        $this->variantStore = $variantStore;
    }

    public function aggregatesForUuid(string $uuid): array
    {
        return VariantTabulator::aggregate($this->variantStore->forSuiteUuid($uuid));
    }

    public function chartForUuid(string $uuid): array
    {
        return VariantTabulator::chart(
            $this->variantStore->forSuiteUuid($uuid)
        );
    }

    public function aggregatesForUuidAndClass(string $uuid, string $class)
    {
        return VariantTabulator::aggregate(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
    }

    public function chartForUuidAndClass(string $uuid, string $class): array
    {
        return VariantTabulator::chart(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
    }
}
