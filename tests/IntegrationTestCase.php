<?php

namespace App\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Infrastructure\Symfony\Kernel;
use App\Tests\Helper\DatabaseHelper;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use App\Service\ProjectService;
use App\Domain\Project\ProjectName;

abstract class IntegrationTestCase extends TestCase
{
    public function container(): ContainerInterface
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        return $kernel->getContainer();
    }

    public function initDatabase(ContainerInterface $container)
    {
        $helper = new DatabaseHelper($container);
        $helper->purge();
    }

    public function initFixtures(ContainerInterface $container)
    {
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
    }

}
