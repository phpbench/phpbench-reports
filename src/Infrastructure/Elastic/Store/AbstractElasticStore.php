<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;

abstract class AbstractElasticStore
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function doStoreMany(string $indexName, array $documents): void
    {
        $params = [
            'body' => [],
        ];
        foreach ($documents as $document) {
            $params['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_type' => $indexName,
                ]
            ];

            $params['body'][] = $document;
        }

        $this->client->bulk($params);
    }

    protected function documentsFromResult(array $result)
    {
        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }
}
