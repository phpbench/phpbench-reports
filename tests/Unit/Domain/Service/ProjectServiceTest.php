<?php

namespace App\Tests\Unit\Domain\Service;

use PHPUnit\Framework\TestCase;
use App\Service\ProjectService;
use App\Domain\Project\Project;
use App\Domain\Project\ProjectRepository;
use App\Domain\User\BenchUser;
use App\Domain\Project\Projects;
use App\Domain\User\BenchUserRepository;
use App\Domain\Project\ProjectName;

class ProjectServiceTest extends TestCase
{
    const USERNAME = 'dantleech';
    const PROJECT_NAMESPACE = 'project_namespace';
    const PROJECT_NAME = 'project_name';

    /**
     * @var ProfileService
     */
    private $profileService;

    /**
     * @var ProjectRepository|ObjectProphecy
     */
    private $projectRepository;

    /**
     * @var ObjectProphecy|BenchUser
     */
    private $user;

    /**
     * @var ObjectProphecy|Project
     */
    private $project1;

    /**
     * @var ObjectProphecy|BenchUserRepository
     */
    private $userRepository;

    public function setUp()
    {
        $this->projectRepository = $this->prophesize(ProjectRepository::class);
        $this->userRepository = $this->prophesize(BenchUserRepository::class);
        $this->user = $this->prophesize(BenchUser::class);
        $this->project1 = $this->prophesize(Project::class);
        $this->profileService = new ProjectService(
            $this->projectRepository->reveal(),
            $this->userRepository->reveal()
        );

        $this->userRepository->findByUsernameOrExplode(self::USERNAME)->willReturn($this->user->reveal());
    }

    public function testProjects()
    {
        $projects = Projects::fromProjects([
            $this->project1->reveal()
        ]);

        $project1 = $this->prophesize(Project::class);
        $this->projectRepository->findForUser($this->user->reveal())->willReturn($projects);

        $result = $this->profileService->projects(self::USERNAME);

        $this->assertEquals($projects, $result);
    }

    public function testCreateProject()
    {
        $this->projectRepository->createProject(
            $this->user->reveal(),
            ProjectName::fromNamespaceAndName(
                self::PROJECT_NAMESPACE,
                self::PROJECT_NAME
            ),
            null
        )->willReturn($this->project1->reveal());

        $result = $this->profileService->createProject(
            self::USERNAME,
            self::PROJECT_NAMESPACE,
            self::PROJECT_NAME
        );

        $this->assertEquals($this->project1->reveal(), $result);
    }

    public function testProject()
    {
        $this->projectRepository->findProject(
            $this->user->reveal(),
            self::PROJECT_NAMESPACE,
            self::PROJECT_NAME
        )->willReturn($this->project1->reveal());

        $result = $this->profileService->project(
            self::USERNAME,
            self::PROJECT_NAMESPACE,
            self::PROJECT_NAME
        );

        $this->assertEquals($this->project1->reveal(), $result);
    }

    public function testUpdateProject()
    {
        $this->projectRepository->updateProject(
            $this->user->reveal(),
            $this->project1->reveal()
        )->shouldBeCalled();

        $this->profileService->updateProject(
            self::USERNAME,
            $this->project1->reveal()
        );
    }
}
