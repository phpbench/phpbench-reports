<?php

namespace App\Service;

use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use App\Domain\SuiteStorage;
use App\Domain\User\BenchUserRepository;
use RuntimeException;
use App\Service\Exception\ImportException;

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

    public function __construct(
        Importer $importer,
        SuiteStorage $storage,
        BenchUserRepository $userRepository
    ) {
        $this->importer = $importer;
        $this->storage = $storage;
        $this->userRepository = $userRepository;
    }

    public function importFromPayload(string $payload, string $apiKey = null): string
    {
        $document = $this->createDocument($payload, $apiKey);

        $id = $this->importer->import($document);

        $this->storage->storePayload($id, $document->saveXML());

        return $id;
    }

    public function importFromFile(string $filename, string $username = null)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf(
                'File "%s" not found',
                $filename
            ));
        }

        $apiKey = null;

        if ($username) {
            $user = $this->userRepository->findByUsernameOrExplode($username);
            $apiKey = $user->apiKey();
        }

        return $this->importFromPayload(file_get_contents($filename), $apiKey);
    }

    private function createDocument(string $payload, string $apiKey = null)
    {
        $document = new Document();
        $document->loadXML($payload);
        $userId = $document->firstChild->getAttribute('user-id');
        $username = $document->firstChild->getAttribute('username');

        if (null === $apiKey && empty($userId)) {
            throw new ImportException(sprintf(
                'No API Key given and document has no user-id'
            ));
        }

        if (empty($userId) || empty($username)) {
            $user = $this->userRepository->findByApiKeyOrExplode($apiKey);
            $document->firstChild->setAttribute('user-id', $user->id());
            $document->firstChild->setAttribute('username', $user->username());
        }

        return $document;
    }
}
