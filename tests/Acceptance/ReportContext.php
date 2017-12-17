<?php

namespace App\Tests\Acceptance;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use App\Service\ImporterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Assert;

class ReportContext implements Context
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
     * @Given I have submitted the suite :filename
     */
    public function iHaveSubmittedTheSuite(string $filename)
    {
        $path = __DIR__ . '/../Fixtures/' . $filename;
        $this->suiteUuid = $this->importerService->importFromFile($path);
    }

    /**
     * @When I view the resulting report
     * @When am viewing the resulting report
     */
    public function iViewTheResultingReport()
    {
        $this->lastResponse = $this->kernel->handle(Request::create('/report/suite/' . $this->suiteUuid));
    }

    /**
     * @Then I should see the results for :subject
     */
    public function iShouldSeeTheResultsFor(string $subject)
    {
        Assert::assertContains($subject, $this->lastResponse->getContent());
    }

    /**
     * @When I click benchmark :class
     */
    public function iClickBenchmark($class)
    {
        $this->lastResponse = $this->kernel->handle(Request::create(
            '/report/suite/' . $this->suiteUuid . '/benchmark/' . $class
        ));
    }

    /**
     * @When I click variant :variant of subject :subject of benchmark :benchmark
     */
    public function iClickVariant($variant, $subject, $class)
    {
        $this->lastResponse = $this->kernel->handle(Request::create(
            '/report/suite/' . $this->suiteUuid . '/benchmark/' . urlencode($class) . '/subject/' . $subject . '/variant/' . $variant
        ));
    }

    /**
     * @Then I should see the iterations report
     */
    public function iShouldSeeTheIterationsReport()
    {
        Assert::assertContains('Mem Peak', $this->lastResponse->getContent());
    }
}
