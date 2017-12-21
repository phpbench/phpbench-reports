<?php

namespace App\Infrastructure\Elastic\Store;

use App\Domain\Store\VariantStore;
use Elasticsearch\Client;

class ElasticVariantStore extends AbstractElasticStore implements VariantStore
{
    const INDEX_NAME = 'phpbench_variant';

    public function storeMany(array $documents): void
    {
        $this->doStoreMany(self::INDEX_NAME, $documents);
    }

    public function forSuiteUuid(string $uuid): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'query' => [
                    'match' => [
                        'suite-uuid.keyword' => $uuid,
                    ],
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }

    public function forSuiteUuidAndBenchmark(string $uuid, string $class): array
    {
        $result = $this->search(self::INDEX_NAME, [
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

        return $this->documentsFromResult($result);
    }
}
