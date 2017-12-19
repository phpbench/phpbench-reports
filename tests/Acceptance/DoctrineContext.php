<?php

namespace App\Tests\Acceptance;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Behat\Behat\Context\Context;

class DoctrineContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(KernelInterface $kernel)
    {
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getEntityManager();
    }

    /**
     * @BeforeScenario
     */
    public function purge()
    {
        $connection = $this->entityManager->getConnection();
        $classes = [ DoctrineProject::class, DoctrineUser::class ];

        foreach ($classes as $class) {
            $metadata = $this->entityManager->getClassMetadata($class);
            $connection->exec('DELETE FROM ' . $metadata->getTableName() . ';');
        }
    }
}
