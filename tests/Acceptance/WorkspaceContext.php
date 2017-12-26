<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Symfony\Component\Filesystem\Filesystem;

class WorkspaceContext implements Context
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $workspaceDir;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->workspaceDir = __DIR__ . '/../Workspace';
    }

    /**
     * @BeforeScenario
     */
    public function initWorkspace()
    {
        if ($this->filesystem->exists($this->workspaceDir)) {
            $this->filesystem->remove($this->workspaceDir);
        }

        $this->filesystem->mkdir($this->workspaceDir);
    }
}
