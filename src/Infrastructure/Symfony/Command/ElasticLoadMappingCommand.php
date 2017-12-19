<?php

namespace App\Infrastructure\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Elasticsearch\Client;
use App\Infrastructure\Elastic\MappingLoader;

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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mappingLoader->loadMappings();
        $output->writeln('Done');
    }
}
