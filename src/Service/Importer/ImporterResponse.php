<?php

namespace App\Service\Importer;

use App\Domain\Project\ProjectName;

class ImporterResponse
{
    /**
     * @var ProjectName
     */
    private $projectName;

    /**
     * @var string
     */
    private $uuid;

    private function __construct(ProjectName $projectName, string $uuid)
    {
        $this->projectName = $projectName;
        $this->uuid = $uuid;
    }

    public static function create(ProjectName $projectName, string $uuid)
    {
        return new self($projectName, $uuid);
    }

    public function project(): ProjectName
    {
        return $this->projectName;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
