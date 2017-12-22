<?php

namespace App\Infrastructure\Elastic\Store;

use App\Domain\Store\VariantStore;
use Elasticsearch\Client;
use App\Domain\Project\ProjectName;

class ElasticVariantStore extends AbstractElasticStore implements VariantStore
{
    const INDEX_NAME = 'phpbench_variant';

    public function storeMany(array $documents): void
    {
        $this->doStoreMany(self::INDEX_NAME, $documents);
    }

    public function forProjectAndClass(ProjectName $projectName, string $class): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'project-name.keyword' => $projectName->name(), ] ],
                            [ 'term' => [ 'project-namespace.keyword' => $projectName->namespace(), ] ],
                            [ 'term' => [ 'benchmark-class.keyword' => $class, ] ],
                        ],
                    ],
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }

    public function forSuiteUuid(string $uuid): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'subject-name.keyword' => 'ASC',
                ],
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
