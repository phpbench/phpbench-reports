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

    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents)
    {
        $filesystem = new Filesystem();
        $path = sprintf('%s/%s/%s.xml', $this->storagePath, $projectId, $suiteUuid);

        if (false === file_exists(dirname($path))) {
            $filesystem->mkdir(dirname($path));
        }

        $filesystem->dumpFile($path, $xmlContents);
    }
}
