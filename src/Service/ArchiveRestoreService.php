<?php

namespace App\Service;

use App\Domain\Archive\ArchiveStorage;
use App\Domain\Import\Importer;
use App\Domain\Archive\ArchiveRestoreProgress;

class ArchiveRestoreService
{
    /**
     * @var ArchiveStorage
     */
    private $storage;

    /**
     * @var Importer
     */
    private $importer;

    public function __construct(ArchiveStorage $storage, Importer $importer)
    {
        $this->storage = $storage;
        $this->importer = $importer;
    }

    public function restoreArchive(ArchiveRestoreProgress $progress)
    {
        $files = $this->storage->list();

        foreach ($files as $file) {
            $document = $this->storage->load(file_get_contents($file->getPathName()));
            $this->importer->import($document);
            $progress->suiteImported($document);
        }
    }
}
