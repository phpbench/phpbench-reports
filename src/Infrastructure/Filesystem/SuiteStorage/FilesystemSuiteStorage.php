<?php

namespace App\Infrastructure\Filesystem\SuiteStorage;

use App\Domain\Archive\ArchiveStorage;
use Symfony\Component\Filesystem\Filesystem;
use App\Infrastructure\Filesystem\SuiteStorage\Writer;
use Iterator;
use RegexIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use PhpBench\Dom\Document;

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

    public function list(): Iterator
    {
        $iterator = new RecursiveDirectoryIterator($this->storagePath);
        $iterator = new RecursiveIteratorIterator($iterator);
        return new RegexIterator($iterator, '{.*xml\.bz2}');
    }

    public function load(string $contents): Document
    {
        $decompressed = bzdecompress($contents);
        $document = new Document();
        $document->loadXML($decompressed);

        return $document;
    }
}
