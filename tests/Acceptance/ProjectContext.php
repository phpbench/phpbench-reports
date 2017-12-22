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
     * @Given user :user has project :namespace :name with API key :apiKey
     */
    public function iHaveAProject($user, $namespace, $name, $apiKey = null)
    {
        $this->projectService->createProject($user, $namespace, $name, $apiKey);
    }

    /**
     * @Then I should see the project :arg1
     */
    public function iShouldSeeTheProject($arg1)
    {
        $element = $this->getSession()->getPage()->find('xpath', '//td[contains(., "' . $arg1 . '")]');
        Assert::assertNotNull($element, 'Project cell was found');
    }

    /**
     * @Given I delete project :subjectName
     */
    public function iDeleteProject($projectName)
    {
        $deleteLink = $this->getSession()->getPage()->find('xpath', '//td[contains(., "' . $projectName . '")]/../td/a[contains(@class, "delete")]');
        Assert::assertNotNull($deleteLink, 'Delete link exists');
        $deleteLink->click();
    }

    /**
     * @Then I should not see project :projectName
     */
    public function iShouldNotSeeProject($projectName)
    {
        $elements = $this->getSession()->getPage()->findAll('xpath', '//td[contains(., "' . $projectName . '")]');
        Assert::assertCount(0, $elements, 'Project is not visible');
    }
}
