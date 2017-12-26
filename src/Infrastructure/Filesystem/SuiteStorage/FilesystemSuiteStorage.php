<?php

namespace App\Infrastructure\Filesystem\SuiteStorage;

use App\Domain\Archive\ArchiveStorage;
use Symfony\Component\Filesystem\Filesystem;
use App\Infrastructure\Filesystem\SuiteStorage\Writer;

class FilesystemSuiteStorage implements ArchiveStorage
{
    /**
     * @var string
     */
    private $storagePath;

    /**
     * @var Writer
     */
    private $writer;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
        $this->writer = new Writer();
    }

    public function storePayload(string $projectId, string $suiteUuid, string $xmlContents): void
    {
        $path = sprintf('%s/%s/%s.xml.bz2', $this->storagePath, $projectId, $suiteUuid);
        $this->writer->write($path, bzcompress($xmlContents));
    }
}
