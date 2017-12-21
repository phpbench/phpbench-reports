<?php

namespace App\Tests\Benchmark;

use App\Tests\Benchmark\BenchCase;
use App\Service\ImporterService;
use App\Domain\Project\ProjectName;
use App\Service\ProjectService;
use App\Service\UserService;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;

/**
 * @BeforeMethods({"init"})
 */
class ImportBench extends BenchCase
{
    /**
     * @var ImporterService
     */
    private $importer;

    public function init()
    {
        $container = $this->container();

        $this->initDatabase($container);

        $userRepository = $container->get(DoctrineUserRepository::class);
        $userRepository->create(
            'test',
            uniqid(),
            '$2y$12$C8sHO2VzPQG0igceHzAG/eYwGmFFciJXq4VMa3BnFDUjsLnGwrYaK' // "test"
        );

        /** @var ProjectService $projectService */
        $projectService = $container->get(ProjectService::class);
        $projectName = ProjectName::fromComposite('test/test');
        $projectService->createProject('test', $projectName->namespace(), $projectName->name());

        $this->importer = $container->get(ImporterService::class);
    }

    /**
     * @Iterations(10)
     */
    public function benchImport()
    {
        $projectName = ProjectName::fromComposite('test/test');
        $this->importer->importFromFile(__DIR__ . '/../Fixtures/suite_full.xml', $projectName);
    }
}
