<?php

namespace App\Domain\Project;

use App\Domain\User\BenchUser;
use App\Domain\Project\Project;

interface ProjectRepository
{
    public function findForUser(BenchUser $benchUser): Projects;

    public function createProject(BenchUser $benchUser, ProjectName $projectName, string $apiKey = null): Project;

    public function findProject(BenchUser $benchUser, string $namespace, string $name): Project;

    public function updateProject(BenchUser $benchUser, Project $project): void;

    public function findByApiKey(string $apiKey):? Project;

    public function findByProjectName(ProjectName $projectName): Project;
}
