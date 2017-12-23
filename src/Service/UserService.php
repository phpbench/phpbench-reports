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
        return $this->userRepository->create($username, uniqid(), $password);
    }
}
