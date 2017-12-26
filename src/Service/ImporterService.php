<?php

namespace App\Service;

use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use App\Domain\Archive\ArchiveStorage;
use App\Domain\User\BenchUserRepository;
use RuntimeException;
use App\Service\Exception\ImportException;
use App\Domain\Project\ProjectRepository;
use App\Domain\Project\ProjectName;
use App\Domain\Import\ImporterResponse;
use PhpBench\Dom\Element;

class ImporterService
{
    /**
     * @var Importer
     */
    private $importer;

    /**
     * @var SuiteStorage
     */
    private $storage;

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    public function __construct(
        Importer $importer,
        ArchiveStorage $storage,
        BenchUserRepository $userRepository,
        ProjectRepository $projectRepository
    ) {
        $this->importer = $importer;
        $this->storage = $storage;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    public function importFromPayload(string $payload, string $apiKey = null): ImporterResponse
    {
        $document = $this->createDocument($payload, $apiKey);
        $response = $this->importer->import($document);

        $this->storage->storePayload(
            $response->projectId(),
            $response->uuid(),
            $document->saveXML()
        );

        return $response;
    }

    public function importFromFile(string $filename, ProjectName $projectName = null): ImporterResponse
    {
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf(
                'File "%s" not found',
                $filename
            ));
        }

        $apiKey = null;

        if ($projectName) {
            $project = $this->projectRepository->findByProjectName($projectName);
            $apiKey = $project->apiKey();
        }

        return $this->importFromPayload(file_get_contents($filename), $apiKey);
    }

    private function createDocument(string $payload, string $apiKey = null)
    {
        $document = new Document();
        $document->loadXML($payload);
        $projectId = $document->firstChild->getAttribute('project-id');
        $username = $document->firstChild->getAttribute('username');

        if (null === $apiKey && empty($projectId)) {
            throw new ImportException(sprintf(
                'No API Key given and document has no project-id'
            ));
        }

        if (empty($projectId) || empty($username)) {
            $this->associateDocumentWithProject($apiKey, $document->firstChild);
        }

        return $document;
    }

    private function associateDocumentWithProject(string $apiKey, Element $element): void
    {
        $project = $this->projectRepository->findByApiKey($apiKey);
        $user = $project->user();
        $element->setAttribute('project-id', $project->id());
        $element->setAttribute('project-namespace', $project->name()->namespace());
        $element->setAttribute('project', (string) $project->name());
        $element->setAttribute('project-name', $project->name()->name());
        $element->setAttribute('user-id', $user->id());
        $element->setAttribute('username', $user->username());
    }
}
