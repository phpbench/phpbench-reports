<?php

namespace App\Tests\Acceptance;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Behat\Behat\Context\Context;
use Elasticsearch\Client;
use App\Infrastructure\Elastic\MappingLoader;

class ElasticContext implements Context
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var MappingLoader
     */
    private $mappingLoader;

    public function __construct(KernelInterface $kernel)
    {
        $this->client = $kernel->getContainer()->get(Client::class);
        $this->mappingLoader = new MappingLoader($this->client);
    }

    /**
     * @BeforeScenario
     */
    public function purge()
    {
        $this->mappingLoader->purgeAll();
        $this->mappingLoader->loadMappings();
    }

    /**
     * @Given the elastic search index was destroyed
     */
    public function elasticSearchIndexHasBeenDestroyed()
    {
        $this->mappingLoader->purgeAll();
        $this->mappingLoader->loadMappings();
    }
}
