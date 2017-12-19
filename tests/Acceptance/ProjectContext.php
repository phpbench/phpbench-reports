<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Service\ProjectService;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\Assert;

class ProjectContext extends RawMinkContext implements Context
{
    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(KernelInterface $kernel)
    {
        $this->projectService = $kernel->getContainer()->get(ProjectService::class);
    }

    /**
     * @Given user :user has project :namespace :name
     */
    public function iHaveAProject($user, $namespace, $name)
    {
        $this->projectService->createProject($user, $namespace, $name);
    }

    /**
     * @Then I should see the project :arg1
     */
    public function iShouldSeeTheProject($arg1)
    {
        $element = $this->getSession()->getPage()->find('xpath', '//td[contains(., "' . $arg1 . '")]');
        Assert::assertNotNull($element, 'Project cell was found');
    }
}
