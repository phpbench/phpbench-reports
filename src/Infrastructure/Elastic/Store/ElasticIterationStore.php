<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\IterationStore;

class ElasticIterationStore implements IterationStore
{
    const INDEX_NAME = 'phpbench_iteration';

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
        $params = [
            'body' => [],
        ];
        foreach ($data as $id => $document) {
            $params['body'][] = [
                'index' => [
                    '_index' => self::INDEX_NAME,
                    '_type' => self::INDEX_NAME,
                ]
            ];

            $params['body'][] = $document;
        }

        $this->client->bulk($params);
    }

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant)
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'size' => 1000,
            'body' => [
                'sort' =>  [
                    'iteration' => 'ASC',
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'suite-uuid.keyword' => $uuid, ] ],
                            [ 'term' => [ 'benchmark-class.keyword' => $class, ] ],
                            [ 'term' => [ 'subject-name.keyword' => $subject, ] ],
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
