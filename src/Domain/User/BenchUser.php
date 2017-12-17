<?php

namespace App\Domain\User;

interface BenchUser
{
    const ROLE_USER = 'user';

    public function vendorId(): string;

    public function username(): string;
}
