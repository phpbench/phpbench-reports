<?php

namespace App\Infrastructure\Elastic;

use Elasticsearch\Client;

class MappingLoader
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function purgeAll()
    {
        foreach (array_keys($this->mappings()) as $indexName) {
            $this->client->indices()->delete([
                'index' => $indexName,
            ]);
        }
    }

    public function loadMappings()
    {

        foreach ($this->mappings() as $indexName => $mappings) {
            $params = [
                'index' => $indexName,
                'body' => $mappings,
            ];
            $this->client->indices()->create($params);
        }
    }

    private function mappings(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../../config/elastic/mapping.json'), true);
    }
}
