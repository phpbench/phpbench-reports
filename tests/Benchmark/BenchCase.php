<?php

namespace App\Tests\Benchmark;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Infrastructure\Symfony\Kernel;
use App\Tests\Helper\DatabaseHelper;

abstract class BenchCase
{
    public function container(): ContainerInterface
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        return $kernel->getContainer();
    }

    public function initDatabase(ContainerInterface $container)
    {
        $helper = new DatabaseHelper($container);
        $helper->purge();
    }
}
