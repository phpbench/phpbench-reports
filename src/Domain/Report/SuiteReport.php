<?php

namespace App\Domain\Report;

use App\Domain\Store\SuiteStore;

class SuiteReport
{
    /**
     * @var SuiteStore
     */
    private $suiteStore;

    public function __construct(
        SuiteStore $suiteStore
    )
    {
        $this->suiteStore = $suiteStore;
    }

    public function allSuites()
    {
        return $this->suiteStore->all();
    }
}
