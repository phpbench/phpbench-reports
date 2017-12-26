<?php

namespace App\Domain\Import;

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

    /**
     * @var string
     */
    private $projectId;

    private function __construct(ProjectName $projectName, string $projectId, string $uuid)
    {
        $this->projectName = $projectName;
        $this->uuid = $uuid;
        $this->projectId = $projectId;
    }

    public static function create(ProjectName $projectName, string $projectId, string $uuid)
    {
        return new self($projectName, $projectId, $uuid);
    }

    public function project(): ProjectName
    {
        return $this->projectName;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }
}
