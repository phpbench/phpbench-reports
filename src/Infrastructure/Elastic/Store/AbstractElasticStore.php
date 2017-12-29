<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Query\ResultSet;

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
        if (empty($documents)) {
            return;
        }

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

    protected function resultSet(array $result): ResultSet
    {
        return ResultSet::create(array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']));
    }

    protected function search(string $indexName, $params)
    {
        $params = array_merge([
            'index' => $indexName,
            'type' => $indexName,
            'size' => 1000,
        ], $params);

        return $this->client->search($params);
    }
}
