<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\IterationStore;
use App\Domain\Query\ResultSet;

class ElasticIterationStore extends AbstractElasticStore implements IterationStore
{
    const INDEX_NAME = 'phpbench_iteration';

    public function storeMany(array $documents): void
    {
        $this->doStoreMany(self::INDEX_NAME, $documents);
    }

    public function forSuiteUuidBenchmarkSubjectAndVariant(string $uuid, string $class, string $subject, string $variant): ResultSet
    {
        $result = $this->search(self::INDEX_NAME, [
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

        return $this->resultSet($result);
    }
}
