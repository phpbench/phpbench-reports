<?php

namespace App\Infrastructure\Filesystem\SuiteStorage;

use App\Domain\SuiteStorage;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemSuiteStorage implements SuiteStorage
{
    /**
     * @var string
     */
    private $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function storePayload(string $id, string $xmlContents)
    {
        $filesystem = new Filesystem();
        $path = sprintf('%s/%s.xml', $this->storagePath, $id);

        if (false === file_exists(dirname($path))) {
            $filesystem->mkdir(dirname($path));
        }

        $filesystem->dumpFile($path, $xmlContents);
    }
}
