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
        foreach ($data as $id => $document) {
            $this->client->index([
                'index' => self::INDEX_NAME,
                'type' => self::INDEX_NAME,
                'id' => $id,
                'body' => $document,
            ]);
        }
    }

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant)
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'suite-uuid' => $uuid, ] ],
                            [ 'term' => [ 'benchmark-class.keyword' => $class, ] ],
                            [ 'term' => [ 'subject-name.keyword' => $subject, ] ],
                            [ 'term' => [ 'variant-index' => $variant, ] ],
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
