<?php

namespace App\Domain\Report;

use App\Domain\Store\SuiteStore;
use App\Domain\User\BenchUserRepository;
use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;

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

    public function allSuites(): ResultSet
    {
        return $this->suiteStore->all();
    }

    public function suitesForNamespace(string $namespace): ResultSet
    {
        return $this->suiteStore->forNamespace($namespace);
    }

    public function suitesForProject(ProjectName $projectName): ResultSet
    {
        return $this->suiteStore->forProject($projectName);
    }

    public function environmentFor($uuid): array
    {
        return $this->suiteStore->forSuiteUuid($uuid);
    }
}
