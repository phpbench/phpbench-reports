<?php

namespace App\Domain\Project;

use App\Domain\User\BenchUser;
use App\Domain\Project\Project;

interface ProjectRepository
{
    public function findForUser(BenchUser $benchUser): Projects;

    public function projectExists(BenchUser $benchUser, string $namespace, string $name): bool;

    public function createProject(BenchUser $benchUser, string $namespace, string $name, string $apiKey): Project;

    public function findProject(BenchUser $benchUser, string $namespace, string $name): Project;

    public function updateProject(BenchUser $benchUser, Project $project): void;

    public function findByApiKey(string $apiKey):? Project;
}
