<?php

namespace App\Tests\Acceptance;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use App\Service\ImporterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Assert;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Domain\Project\ProjectName;

class ReportContext extends RawMinkContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var ImporterService
     */
    private $importerService;
    private $suiteUuid;

    /**
     * @var Response
     */
    private $lastResponse;

    public function __construct(KernelInterface $kernel, ImporterService $importerService)
    {
        $this->kernel = $kernel;
        $this->importerService = $importerService;
    }

    /**
     * @Given I have submitted the suite :filename for project :projectName
     */
    public function iHaveSubmittedTheSuite(string $filename, string $projectName)
    {
        $path = __DIR__ . '/../Fixtures/' . $filename;
        $this->suiteUuid = $this->importerService->importFromFile($path, ProjectName::fromComposite($projectName));
        // let elastic settle down (race condition issues)
        sleep(1);
    }

    /**
     * @Then I should see the results for :subject
     */
    public function iShouldSeeTheResultsFor(string $subject)
    {
        Assert::assertContains($subject, $this->getSession()->getPage()->getContent());
    }

    /**
     * @When I click benchmark :class
     */
    public function iClickBenchmark($class)
    {
        $this->getSession()->getPage()->clickLink($class);
    }

    /**
     * @When I click variant :variant
     */
    public function iClickVariant($variant)
    {
        $this->getSession()->getPage()->clickLink($variant);
    }

    /**
     * @Then I should see the iterations report
     */
    public function iShouldSeeTheIterationsReport()
    {
        Assert::assertNotNull($this->getSession()->getPage()->find(
            'css',
            'table.ac-iterations'
        ), 'Iterations table is present');
    }

    /**
     * @Then the suite with UUID :arg1 should be listed
     */
    public function theSuiteWithUuidShouldBeListed($arg1)
    {
        Assert::assertNotNull($this->getSession()->getPage()->find(
            'xpath',
            '//td[contains(., \'' . $arg1 . '\')]'
        ), 'Suite UUID is present');
    }

    /**
     * @Then all suites should be listed
     */
    public function allSitesAreListed()
    {
        Assert::assertNotNull($this->getSession()->getPage()->find(
            'css',
            'table.ac-suites'
        ), 'Suites table is present');
    }

    /**
     * @Then I should see an error row for :subjectName
     */
    public function iShouldSeeAnErrorRowFor($subjectName)
    {
        $errorRows = $this->getSession()->getPage()->findAll(
            'xpath',
            '//td[contains(@class, "ac-error")][contains(., "' . $subjectName . '")]'
        );
        Assert::assertCount(1, $errorRows, 'Found error row');
    }
}
