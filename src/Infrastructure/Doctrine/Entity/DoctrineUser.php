<?php

namespace App\Infrastructure\Doctrine\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\BenchUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class DoctrineUser implements UserInterface, BenchUser
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
     * @ORM\Column(type="json", nullable=false)
     */
    private $roles = [];

    public function __construct(string $username, string $vendorId, string $password = null, array $roles = [])
    {
        $this->username = $username;
        $this->vendorId = $vendorId;
        $this->password = $password;
        $this->roles = $roles;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return $this->roles;
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
        return null;
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

    public function id(): string
    {
        return $this->id;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
