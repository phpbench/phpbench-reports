<?php

namespace App\Domain\User;

use App\Domain\User\BenchUser;

interface BenchUserRepository
{
    public function create(
        string $username,
        string $githubId,
        string $password = null,
        array $roles = []
    ): BenchUser;

    public function findByVendorId($githubId):? BenchUser;

    public function findByUsername(string $username):? BenchUser;

    public function findByUsernameOrExplode(string $username): BenchUser;

    public function update(BenchUser $user): void;
}
