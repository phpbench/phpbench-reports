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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
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

    public function __construct(string $username, string $vendorId)
    {
        $this->username = $username;
        $this->vendorId = $vendorId;
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
}
