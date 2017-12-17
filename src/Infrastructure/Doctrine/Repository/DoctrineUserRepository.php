<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\User\BenchUserRepository;
use Doctrine\ORM\EntityManager;
use App\Infrastructure\Doctrine\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\BenchUser;
use Doctrine\ORM\NoResultException;
use App\Domain\User\TokenGenerator;

class DoctrineUserRepository implements BenchUserRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(EntityManagerInterface $entityManager, TokenGenerator $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function create(string $username, string $vendorId, string $password = null): BenchUser
    {
        $user = new User($username, $vendorId, $this->tokenGenerator->generate(), $password);
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

    public function findByUsername(string $username):? BenchUser
    {
        try {
            return $user = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.username = :username')
                ->setParameter('username', $username)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
        }

        return null;
    }
}
