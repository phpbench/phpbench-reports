<?php

namespace App\Infrastructure\Filesystem\SuiteStorage;

use Symfony\Component\Filesystem\Filesystem;

class Writer
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function write(string $path, string $contents)
    {
        if (false === $this->filesystem->exists(dirname($path))) {
            $this->filesystem->mkdir(dirname($path));
        }

        $this->filesystem->dumpFile($path, $contents);
    }
}
