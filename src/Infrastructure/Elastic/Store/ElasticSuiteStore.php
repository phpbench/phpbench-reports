<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;
use App\Domain\Query\ResultSet;

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

    public function forNamespace(string $namespace): ResultSet
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

        return $this->resultSet($result);
    }

    public function all(): ResultSet
    {
        $result = $this->search(self::INDEX_NAME, [
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
            ],
        ]);

        return $this->resultSet($result);
    }

    public function forProject(ProjectName $project): ResultSet
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

        return $this->resultSet($result);
    }
}
