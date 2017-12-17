<?php

namespace App\Domain\User;

class TokenGenerator
{
    public function generate()
    {
        return bin2hex(random_bytes(16));
    }
}
