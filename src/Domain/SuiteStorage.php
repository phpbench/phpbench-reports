<?php

namespace App\Domain;

interface SuiteStorage
{
    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents);
}
