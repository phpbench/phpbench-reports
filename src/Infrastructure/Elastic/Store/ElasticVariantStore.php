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

    public function storeMany(array $documents): void
    {
        $params = [
            'body' => [],
        ];
        foreach ($documents as $id => $document) {
            $params['body'][] = [
                'index' => [
                    '_index' => self::INDEX_NAME,
                    '_type' => self::INDEX_NAME,
                    '_id' => $id,
                ]
            ];

            $params['body'][] = $document;
        }

        $this->client->bulk($params);
    }

    public function forSuiteUuid(string $uuid): array
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'body' => [
                'query' => [
                    'match' => [
                        'suite-uuid.keyword' => $uuid,
                    ],
                ],
            ],
        ]);

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): array
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'suite-uuid.keyword' => $uuid, ] ],
                            [ 'term' => [ 'benchmark-class.keyword' => $class, ] ],
                        ],
                    ],
                ],
            ],
        ]);

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }
}
