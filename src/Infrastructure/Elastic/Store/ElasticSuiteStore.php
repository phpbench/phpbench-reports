<?php

namespace App\Infrastructure\Elastic\Store;

use Elasticsearch\Client;
use App\Domain\Store\SuiteStore;
use App\Domain\Project\ProjectName;

/**
 * TODO: Refactor the "stores" to use an abstract class for e.g. extracting the results.
 */
class ElasticSuiteStore implements SuiteStore
{
    const INDEX_NAME = 'phpbench_suite';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

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
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'size' => 1000,
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

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }

    public function forNamespace(string $namespace): array
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'size' => 1000,
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

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }

    public function all(): array
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'size' => 1000,
            'body' => [
                'sort' =>  [
                    'suite-date.keyword' => 'DESC',
                ],
            ],
        ]);

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }

    public function forProject(ProjectName $project): array
    {
        $result = $this->client->search([
            'index' => self::INDEX_NAME,
            'type' => self::INDEX_NAME,
            'size' => 1000,
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

        return array_map(function ($hit) {
            return $hit['_source'];
        }, $result['hits']['hits']);
    }
}
