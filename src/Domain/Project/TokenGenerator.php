<?php

namespace App\Domain\Project;

class TokenGenerator
{
    public function generate()
    {
        return bin2hex(random_bytes(16));
    }
}
