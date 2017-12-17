<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\User\BenchUserRepository;
use Doctrine\ORM\EntityManager;
use App\Infrastructure\Doctrine\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\BenchUser;
use Doctrine\ORM\NoResultException;

class DoctrineUserRepository implements BenchUserRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $username, string $vendorId): BenchUser
    {
        $user = new User($username, $vendorId);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function findByVendorId($githubId):? BenchUser
    {
        try {
            return $user = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.vendorId = :vendorId')
                ->setParameter('vendorId', $githubId)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
        }

        return null;
    }
}
