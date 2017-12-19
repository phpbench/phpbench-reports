<?php

namespace App\Domain\User;

interface BenchUser
{
    const ROLE_USER = 'ROLE_USER';

    public function vendorId(): string;

    public function username(): string;

    public function id(): string;
}
