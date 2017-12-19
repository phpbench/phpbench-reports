<?php

namespace App\Domain\Project;

use DateTimeImmutable;

/**
 * TODO: Use ProjectName VO
 */
interface Project
{
    public function name(): ProjectName;

    public function apiKey(): string;

    public function active(): bool;

    public function id(): string;
}
