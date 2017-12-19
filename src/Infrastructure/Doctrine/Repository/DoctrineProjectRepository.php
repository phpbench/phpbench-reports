<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Project\ProjectRepository;
use App\Domain\Project\Projects;
use App\Domain\User\BenchUser;
use App\Domain\Project\Project;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\TokenGenerator;

class DoctrineProjectRepository implements ProjectRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(EntityManagerInterface $entityManager, TokenGenerator $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function findForUser(BenchUser $benchUser): Projects
    {
        $projects = $this->queryBuilder('p')
            ->where('p.user = :userId')
            ->setParameter('userId', $benchUser->id())
            ->getQuery()
            ->execute();

        return Projects::fromProjects($projects);
    }

    public function projectExists(BenchUser $benchUser, string $namespace, string $name): bool
    {
    }

    public function createProject(BenchUser $benchUser, string $namespace, string $name): Project
    {
        $apiKey = $this->tokenGenerator->generate();
            
        $project = new DoctrineProject($benchUser, $namespace, $name, $apiKey);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function findProject(BenchUser $benchUser, string $namespace, string $name): Project
    {
    }

    public function updateProject(BenchUser $benchUser, Project $project): void
    {
    }
    private function queryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(DoctrineProject::class)->createQueryBuilder('p');
    }
}
