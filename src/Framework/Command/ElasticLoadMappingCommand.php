<?php

namespace App\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Elasticsearch\Client;
use App\Infrastructure\Elastic\MappingLoader;
use Symfony\Component\Console\Input\InputOption;

class ElasticLoadMappingCommand extends Command
{
    /**
     * @var MappingLoader
     */
    private $mappingLoader;

    public function __construct(MappingLoader $mappingLoader)
    {
        $this->mappingLoader = $mappingLoader;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('elastic:mapping:load');
        $this->addOption('purge', null, InputOption::VALUE_NONE, 'Purge all the indexes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('purge')) {
            $this->mappingLoader->purgeAll();
        }
        $this->mappingLoader->loadMappings();
        $output->writeln('Done');
    }
}
