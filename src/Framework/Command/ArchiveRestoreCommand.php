<?php

namespace App\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ArchiveRestoreService;

class ArchiveRestoreCommand extends Command
{
    /**
     * @var ArchiveRestoreService
     */
    private $service;

    public function __construct(ArchiveRestoreService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    protected function configure()
    {
        $this->setName('archive:restore');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->service->restoreArchive();
    }
}
