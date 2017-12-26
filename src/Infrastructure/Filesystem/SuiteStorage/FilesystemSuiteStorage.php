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

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
        $this->filesystem = new Filesystem();
    }

    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents)
    {
        $path = sprintf('%s/%s/%s.xml', $this->storagePath, $projectId, $suiteUuid);

        if (false === $filesystem->exists(dirname($path))) {
            $this->filesystem->mkdir(dirname($path));
        }

        $this->filesystem->dumpFile($path, $xmlContents);
    }
}
