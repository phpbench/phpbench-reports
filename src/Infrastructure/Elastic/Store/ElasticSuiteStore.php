<?php

namespace App\Infrastructure\Elastic\Store;

use App\Domain\Store\VariantStore;
use Elasticsearch\Client;
use App\Domain\Store\SuiteStore;

class ElasticSuiteStore implements SuiteStore
{
    const INDEX_NAME = 'phpbench_suite';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(string $uuid, array $data): void
    {
        foreach ($data as $document) {
            $this->client->index([
                'index' => self::INDEX_NAME,
                'type' => self::INDEX_NAME,
                'id' => $uuid,
                'body' => $document,
            ]);
        }
    }

    public function forSuiteUuid(string $uuid): array
    {
        $result = $this->client->get([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'id' => $uuid,
        ]);

        return $result['_source'];
    }
}
