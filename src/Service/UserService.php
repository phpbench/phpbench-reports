<?php

namespace App\Service;

use App\Domain\User\BenchUserRepository;
use App\Domain\User\BenchUser;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserService
{
    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(BenchUserRepository $userRepository, EncoderFactoryInterface $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
    }

    public function createLocalUser(string $username, string $password): BenchUser
    {
        // using bcrypt, to no salt
        $password = $this->encoderFactory->getEncoder(BenchUser::class)->encodePassword($password, null);
        return $this->userRepository->create($username, uniqid(), $password, [ BenchUser::ROLE_USER ]);
    }

    public function grantRoles(string $username, array $roles)
    {
        $user = $this->userRepository->findByUsername($username);
        $user->setRoles($roles);
        $this->userRepository->update($user);
    }

    public function findOrCreateForVendor(string $username, int $vendorId): BenchUser
    {
        $user = $this->userRepository->findByVendorId($vendorId);

        if (null === $user) {
            $user = $this->userRepository->create($username, $vendorId, null, [ BenchUser::ROLE_USER ]);
        }

        return $user;
    }
}
