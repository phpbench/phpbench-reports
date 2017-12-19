<?php

namespace App\Domain\User;

interface BenchUserRepository
{
    public function create(string $username, string $githubId, string $password = null): BenchUser;

    public function findByVendorId($githubId):? BenchUser;

    public function findByUsername(string $username):? BenchUser;

    public function findByUsernameOrExplode(string $username): BenchUser;

    public function findByApiKey(string $apiKey):? BenchUser;

    public function findByApiKeyOrExplode(string $apiKey): BenchUser;
}
