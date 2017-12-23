<?php

namespace App\Framework\Form;

class ProjectDto
{
    public $name;
    public $namespace;

    public function fromNamespace(string $namespace)
    {
        $new = new self();
        $new->namespace = $namespace; 

        return $new;
    }
}
