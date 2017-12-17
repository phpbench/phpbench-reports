<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\User\BenchUserRepository;
use Doctrine\ORM\EntityManager;
use App\Infrastructure\Doctrine\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\BenchUser;
use Doctrine\ORM\NoResultException;
use App\Domain\User\TokenGenerator;
use RuntimeException;

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

    public function create(string $username, string $vendorId, string $password = null, $apiKey = null): BenchUser
    {
        $apiKey = $apiKey ?: $this->tokenGenerator->generate();
        $user = new User($username, $vendorId, $apiKey, $password);
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

    public function findByUsernameOrExplode(string $username): BenchUser
    {
        $user = $this->findByUsername($username);

        if ($user) {
            return $user;
        }

        throw new RuntimeException(sprintf(
            'Could not find user by username "%s"',
            $username
        ));
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

    public function findByApiKeyOrExplode($apiKey): BenchUser
    {
        if ($user = $this->findByApiKey($apiKey)) {
            return $user;
        }

        throw new RuntimeException(sprintf(
            'Could not find user with API key "%s"',
            $apiKey
        ));
    }

    public function findByApiKey($apiKey):? BenchUser
    {
        try {
            return $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.apiKey = :apiKey')
                ->setParameter('apiKey', $apiKey)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
        }

        return null;
    }
}
