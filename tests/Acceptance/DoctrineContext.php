<?php

namespace App\Tests\Acceptance;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Behat\Behat\Context\Context;
use App\Tests\Helper\DatabaseHelper;

class DoctrineContext implements Context
{
    /**
     * @var DatabaseHelper
     */
    private $helper;

    public function __construct(KernelInterface $kernel)
    {
        $this->helper = new DatabaseHelper($kernel->getContainer());
    }

    /**
     * @BeforeScenario
     */
    public function purge()
    {
        $this->helper->purge();
    }
}
