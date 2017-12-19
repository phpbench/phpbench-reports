<?php

namespace App\Domain\Project;

final class Projects implements \IteratorAggregate
{
    private $projects = [];

    private function __construct($projects)
    {
        foreach ($projects as $projet) {
            $this->add($projet);
        }
    }

    public static function fromProjects(array $projects): Projects
    {
         return new self($projects);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->projects);
    }

    private function add(Project $project)
    {
        $this->projects[] = $project;
    }
}
