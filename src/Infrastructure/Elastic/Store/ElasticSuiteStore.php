<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;

class ElasticSuiteStore extends AbstractElasticStore implements SuiteStore
{
    const INDEX_NAME = 'phpbench_suite';

    public function store(string $uuid, array $data): void
    {
        foreach ($data as $document) {
            $this->client->index([
                'index' => self::INDEX_NAME,
                'type' => self::INDEX_NAME,
                'id' => $uuid,
                'body' => $document,
            ]);
        }
    }

    public function forSuiteUuid(string $uuid): array
    {
        $result = $this->client->get([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'id' => $uuid,
        ]);

        return $result['_source'];
    }

    public function forUserId(string $userId): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
                'query' => [
                    'match' => [
                        'user-id' => $userId,
                    ],
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }

    public function forNamespace(string $namespace): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
                'query' => [
                    'match' => [
                        'project-namespace.keyword' => $namespace,
                    ],
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }

    public function all(): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }

    public function forProject(ProjectName $project): array
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'project-namespace.keyword' => $project->namespace(), ] ],
                            [ 'term' => [ 'project-name.keyword' => $project->name(), ] ],
                        ],
                    ],
                ],
            ],
        ]);

        return $this->documentsFromResult($result);
    }
}
