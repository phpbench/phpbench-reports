<?php

namespace App\Domain\Archive;

use FilesystemIterator;
use Iterator;
use PhpBench\Dom\Document;

interface ArchiveStorage
{
    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents): void;

    public function list(): Iterator;

    public function load(string $contents): Document;
}
