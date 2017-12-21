<?php

namespace App\Domain\Report;

use App\Domain\Store\SuiteStore;
use App\Domain\User\BenchUserRepository;

class SuiteReport
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

    public function allSuites(): array
    {
        return $this->suiteStore->all();
    }

    public function suitesForUser(string $username): array
    {
        $user = $this->userRepository->findByUsernameOrExplode($username);

        return $this->suiteStore->forUserId($user->id());
    }

    public function suitesForNamespace(string $namespace): array
    {
        return $this->suiteStore->forNamespace($namespace);
    }

    public function environmentFor($uuid)
    {
        return $this->suiteStore->forSuiteUuid($uuid);
    }
}
