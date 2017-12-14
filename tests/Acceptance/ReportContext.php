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
    private $result;

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
        $this->result = $this->importerService->importFromFile($path);
    }

    /**
     * @When I view the resulting report
     */
    public function iViewTheResultingReport()
    {
        $this->lastResponse = $this->kernel->handle(Request::create('/report/aggregate/suite/' . $this->result));
    }

    /**
     * @Then I should see the results for :subject
     */
    public function iShouldSeeTheResultsFor(string $subject)
    {
        Assert::assertContains($subject, $this->lastResponse->getContent());
    }
}
