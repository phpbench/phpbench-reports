<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PhpBench\Dom\Document;
use App\Domain\Import\Importer;
use Symfony\Component\Finder\Finder;
use RuntimeException;
use App\Service\ImporterService;
use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends Command
{
    /**
     * @var ImporterService
     */
    private $importer;

    public function __construct(ImporterService $importer)
    {
        $this->importer = $importer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('phpbench:import');
        $this->addArgument('path', InputArgument::REQUIRED, 'PHPBench XML file');
        $this->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'Username');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if (is_file($path)) {
            return $this->import($output, $path);
        }

        if (!file_exists($path)) {
            throw new RuntimeException(sprintf(
                'File "%s" does not exist',
                $path
            ));
        }

        $finder = Finder::create()
            ->in($path)
            ->files()
            ->name('*.xml');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $output->writeln(sprintf('<info>Importing</> <comment>"</>%s<comment>"</>', $file->getPathname()));
            $this->importer->importFromFile($file->getPathname(), $input->getOption('username'));
        }

        $output->writeln('Done');
    }
}
