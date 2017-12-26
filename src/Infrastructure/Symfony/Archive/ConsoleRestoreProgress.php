<?php

namespace App\Infrastructure\Symfony\Archive;

use App\Domain\Archive\ArchiveRestoreProgress;
use PhpBench\Dom\Document;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleRestoreProgress implements ArchiveRestoreProgress
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function suiteImported(Document $document)
    {
        $this->output->writeln(sprintf(
            "<comment>[</>%s<comment>] </>%s",
            $document->firstChild->getAttribute('project'),
            $document->queryOne('suite')->getAttribute('uuid')
        ));
    }
}
