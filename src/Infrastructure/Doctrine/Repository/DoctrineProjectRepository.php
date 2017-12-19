<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Project\ProjectRepository;
use App\Domain\Project\Projects;
use App\Domain\User\BenchUser;
use App\Domain\Project\Project;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Project\TokenGenerator;
use App\Domain\Project\ProjectName;

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

    public function createProject(BenchUser $benchUser, string $namespace, string $name, string $apiKey = null): Project
    {
        $apiKey = $apiKey ?: $this->tokenGenerator->generate();
            
        $project = new DoctrineProject($benchUser, $namespace, $name, $apiKey);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function findProject(BenchUser $benchUser, string $namespace, string $name): Project
    {
        return $this->queryBuilder('p')
            ->where('p.user = :userId')
            ->andWhere('p.namespace = :namespace')
            ->andWhere('p.name = :name')
            ->setParameter('userId', $benchUser->id())
            ->setParameter('namespace', $namespace)
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleResult();
    }

    public function updateProject(BenchUser $benchUser, Project $project): void
    {
    }

    private function queryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(DoctrineProject::class)->createQueryBuilder('p');
    }

    public function projectExists(BenchUser $benchUser, string $namespace, string $name): bool
    {
    }

    public function findByApiKey(string $apiKey):? Project
    {
        return $this->queryBuilder('p')
            ->where('p.apiKey = :apiKey')
            ->setParameter('apiKey', $apiKey)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByProjectName(ProjectName $projectName): Project
    {
        return $this->queryBuilder('p')
            ->where('p.namespace = :namespace')
            ->andWhere('p.name = :name')
            ->setParameter('namespace', $projectName->namespace())
            ->setParameter('name', $projectName->name())
            ->getQuery()
            ->getSingleResult();
    }
}
