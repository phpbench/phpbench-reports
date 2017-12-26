<?php

namespace App\Domain\Archive;

use PhpBench\Dom\Document;

interface ArchiveRestoreProgress
{
    public function suiteImported(Document $document);
}
