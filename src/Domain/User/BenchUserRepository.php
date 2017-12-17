<?php

namespace App\Domain\User;

use App\Domain\User\BenchUser;

interface BenchUserRepository
{
    public function create(string $username, string $githubId, string $password = null): BenchUser;

    public function findByVendorId($githubId):? BenchUser;

    public function findByUsername(string $username):? BenchUser;

    public function findByApiKey($credentials):? BenchUser;
}
