<?php

namespace App\Domain\Report;

use App\Domain\Store\SuiteStore;
use App\Domain\User\BenchUserRepository;
use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;
use App\Domain\Query\PagerContext;

class SuiteReports
{
    /**
     * @var SuiteStore
     */
    private $suiteStore;

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    public function __construct(
        SuiteStore $suiteStore,
        BenchUserRepository $userRepository
    ) {
        $this->suiteStore = $suiteStore;
        $this->userRepository = $userRepository;
    }

    public function allSuites(PagerContext $pager): ResultSet
    {
        return $this->suiteStore->all($pager);
    }

    public function suitesForNamespace(PagerContext $pager, string $namespace): ResultSet
    {
        return $this->suiteStore->forNamespace($pager, $namespace);
    }

    public function suitesForProject(PagerContext $pager, ProjectName $projectName): ResultSet
    {
        return $this->suiteStore->forProject($pager, $projectName);
    }

    public function environmentFor($uuid): array
    {
        return $this->suiteStore->forSuiteUuid($uuid);
    }
}
