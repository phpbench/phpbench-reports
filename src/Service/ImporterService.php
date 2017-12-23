<?php

namespace App\Service;

use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use App\Domain\SuiteStorage;
use App\Domain\User\BenchUserRepository;
use RuntimeException;
use App\Service\Exception\ImportException;
use App\Domain\Project\ProjectRepository;
use App\Domain\Project\ProjectName;
use App\Service\Importer\ImporterResponse;

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
        SuiteStorage $storage,
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

        $id = $this->importer->import($document);
        $this->storage->storePayload($id, $document->saveXML());


        return $this->importerResponse($id, $document);
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
            $project = $this->projectRepository->findByApiKey($apiKey);
            $user = $project->user();
            $document->firstChild->setAttribute('project-id', $project->id());
            $document->firstChild->setAttribute('project-namespace', $project->name()->namespace());
            $document->firstChild->setAttribute('project', (string) $project->name());
            $document->firstChild->setAttribute('project-name', $project->name()->name());
            $document->firstChild->setAttribute('user-id', $user->id());
            $document->firstChild->setAttribute('username', $user->username());
        }

        return $document;
    }

    private function importerResponse($id, Document $document): ImporterResponse
    {
        $projectName = ProjectName::fromNamespaceAndName(
            $document->firstChild->getAttribute('project-namespace'),
            $document->firstChild->getAttribute('project-name')
        );

        return ImporterResponse::create($projectName, $id);
    }
}
