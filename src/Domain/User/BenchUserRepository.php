<?php

namespace App\Domain\User;

use App\Domain\User\BenchUser;

interface BenchUserRepository
{
    public function create(string $username, string $githubId): BenchUser;

    public function findByVendorId($githubId):? BenchUser;
}
