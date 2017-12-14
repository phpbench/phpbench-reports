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
     * @When I upload the suite :filename
     */
    public function iUploadTheSuite($filename)
    {
        $path = __DIR__ . '/../Fixtures/' . $filename;
        $this->response = $this->kernel->handle(Request::create(
            '/import',
            'POST',
            [], [], [], [],
            file_get_contents($path)
        ));
    }

    /**
     * @Then I should a confirmation with the URL :arg1
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
