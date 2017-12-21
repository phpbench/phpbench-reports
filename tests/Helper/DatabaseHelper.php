<?php

namespace App\Tests\Helper;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;

class DatabaseHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get('doctrine')->getEntityManager();
    }

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
