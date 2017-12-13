<?php

namespace App\Domain;

interface SuiteStorage
{
    public function storePayload(string $id, string $xmlContents);
}
