<?php

namespace App\Tests\Integration;

use App\Tests\IntegrationTestCase;
use App\Service\ImporterService;
use App\Domain\Project\ProjectName;
use App\Service\ImporterResponse;

class ImporterTest extends IntegrationTestCase
{
    /**
     * @var ImporterService
     */
    private $importer;

    public function setUp()
    {
        $container = $this->container();
        $this->initFixtures($container);
        $this->importer = $container->get(ImporterService::class);
    }

    public function testImportErrors()
    {
        $projectName = ProjectName::fromComposite('test/test');
        $response = $this->importer->importFromFile(__DIR__ . '/../Fixtures/errors.xml', $projectName);
        $this->assertInstanceOf(ImporterResponse::class, $response);
    }
}
