<?php

namespace App\Domain\Project;

use DateTimeImmutable;

interface Project
{
    public function namespace(): string;

    public function name(): string;

    public function apiKey(): string;

    public function active(): bool;
}
