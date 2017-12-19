<?php

namespace App\Infrastructure\Doctrine\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\BenchUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface, BenchUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $vendorId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;

    public function __construct(string $username, string $vendorId, string $apiKey, string $password = null)
    {
        $this->username = $username;
        $this->vendorId = $vendorId;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    public function updateApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return [ BenchUser::ROLE_USER ];
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->username();
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }

    public function vendorId(): string
    {
        return $this->vendorId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }

    public function id(): string
    {
        return $this->id;
    }
}
