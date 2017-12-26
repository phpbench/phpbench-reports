<?php

namespace App\Domain\Archive;

interface ArchiveStorage
{
    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents): void;
}
