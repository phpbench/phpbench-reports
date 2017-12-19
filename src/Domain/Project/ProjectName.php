<?php

namespace App\Domain\Project;

use InvalidArgumentException;

final class ProjectName
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $name;

    private function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    public static function fromNamespaceAndName(string $namespace, string $name)
    {
        return new self($namespace, $name);
    }

    public static function fromComposite(string $string)
    {
        $parts = explode('/', $string);

        if (count($parts) !== 2) {
            throw new InvalidArgumentException(sprintf(
                'Invalid project name "%s"', $string
            ));
        }

        return new self($parts[0], $parts[1]);
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function name(): string
    {
        return $this->name;
    }
}
