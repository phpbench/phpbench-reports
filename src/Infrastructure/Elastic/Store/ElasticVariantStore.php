<?php

namespace App\Infrastructure\Elastic\Store;

use App\Domain\Store\VariantStore;
use Elasticsearch\Client;

class ElasticVariantStore implements VariantStore
{
    const INDEX_NAME = 'phpbench_variant';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(string $id, array $data): void
    {
        $this->client->index([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'id' => $id,
            'body' => $data,
        ]);
    }
}
