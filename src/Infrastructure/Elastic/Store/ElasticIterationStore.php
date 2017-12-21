<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\IterationStore;

class ElasticIterationStore extends AbstractElasticStore implements IterationStore
{
    const INDEX_NAME = 'phpbench_iteration';

    public function storeMany(array $documents): void
    {
        $this->doStoreMany(self::INDEX_NAME, $documents);
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
