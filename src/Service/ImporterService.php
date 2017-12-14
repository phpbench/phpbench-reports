<?php

namespace App\Service;

use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use App\Domain\SuiteStorage;
use RuntimeException;

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

    public function __construct(Importer $importer, SuiteStorage $storage)
    {
        $this->importer = $importer;
        $this->storage = $storage;
    }

    public function importFromPayload(string $payload): string
    {
        $document = new Document();
        $document->loadXML($payload);
        $id = $this->importer->import($document);
        $this->storage->storePayload($id, $payload);

        return $id;
    }

    public function importFromFile(string $filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf(
                'File "%s" not found', $filename
            ));
        }

        return $this->importFromPayload(file_get_contents($filename));
    }
}
