<?php

namespace App\Infrastructure\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Elasticsearch\Client;

class ElasticLoadMappingCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('elastic:mapping:load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $allMappings = json_decode(file_get_contents(__DIR__ . '/../../../../config/elastic/mapping.json'), true);

        foreach ($allMappings as $indexName => $mappings) {
            $output->writeln('<info>Loading mapping for</>: ' . $indexName);
            $params = [
                'index' => $indexName,
                'body' => $mappings,
            ];
            $this->client->indices()->create($params);
        }
    }
}
