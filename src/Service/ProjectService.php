<?php

namespace App\Service;

use App\Domain\Project\Projects;
use App\Domain\Project\ProjectRepository;
use App\Domain\User\BenchUser;
use App\Domain\Project\Project;
use App\Domain\User\BenchUserRepository;
use App\Domain\Project\ProjectName;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        BenchUserRepository $userRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    public function projects(string $username): Projects
    {
        return $this->projectRepository->findForUser($this->user($username));
    }

    public function createProject(string $username, string $namespace, string $name, string $apiKey = null): Project
    {
        return $this->projectRepository->createProject(
            $this->user($username),
            ProjectName::fromNamespaceAndName($namespace, $name),
            $apiKey
        );
    }

    public function project(string $username, string $namespace, string $name): Project
    {
        return $this->projectRepository->findProject($this->user($username), $namespace, $name);
    }

    public function updateProject(string $username, Project $project): void
    {
        $this->projectRepository->updateProject($this->user($username), $project);
    }

    public function deleteProject(string $username, string $uuid): void
    {
        $this->projectRepository->deleteProject($this->user($username), $uuid);
    }

    private function user(string $username): BenchUser
    {
        return $this->userRepository->findByUsernameOrExplode($username);
    }
}
