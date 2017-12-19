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
     * @Given I have submitted the suite :filename as :username
     */
    public function iHaveSubmittedTheSuite(string $filename, string $username)
    {
        $path = __DIR__ . '/../Fixtures/' . $filename;
        $this->suiteUuid = $this->importerService->importFromFile($path, $username);
        // let elastic settle down (race condition issues)
        usleep(500000);
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
}
