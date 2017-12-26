<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Assert;

class ImportContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Response
     */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I post the suite :filename with API key :apiKey
     */
    public function iUploadTheSuite($filename, $apiKey)
    {
        $path = __DIR__ . '/../Fixtures/' . $filename;
        $request = Request::create(
            '/import',
            'POST',
            [], [], [], [],
            file_get_contents($path)
        );
        $request->headers->set('X-API-Key', $apiKey);
        $this->response = $this->kernel->handle($request);
    }

    /**
     * @Given I posted the suite :filename with API key :apiKey
     */
    public function iUploadedTheSuite($filename, $apiKey)
    {
        $this->iUploadTheSuite($filename, $apiKey);
        $this->theHttpStatusShouldBe(200);
    }


    /**
     * @Then the HTTP status should be :code
     */
    public function theHttpStatusShouldBe($code)
    {
        Assert::assertEquals($code, $this->response->getStatusCode());
    }

    /**
     * @Then I receive confirmation with the URL :arg1
     */
    public function iShouldAConfirmationWithTheUrl($url)
    {
        Assert::assertEquals(
            json_encode([
                'suite_url' => $url,
            ]),
            $this->response->getContent()
        );
    }
}
