<?php

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use App\Domain\User\BenchUserRepository;
use App\Service\UserService;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Domain\User\BenchUser;

class UserServiceTest extends TestCase
{
    /**
     * @var ObjectProphecy|BenchUserRepository
     */
    private $userRepository;

    /**
     * @var ObjectProphecy|EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var UserService
     */
    private $userService;

    public function setUp()
    {
        $this->userRepository = $this->prophesize(BenchUserRepository::class);
        $this->encoderFactory = $this->prophesize(EncoderFactoryInterface::class);

        $this->userService = new UserService(
            $this->userRepository->reveal(),
            $this->encoderFactory->reveal()
        );

        $this->user = $this->prophesize(BenchUser::class);
    }

    public function testCreatesUserForVendorIfNotExists()
    {
        $vendorId = 1234;
        $username = 'dantleech';

        $this->userRepository->findByVendorId($vendorId)->willReturn(null);
        $this->userRepository->create($username, $vendorId)->willReturn($this->user->reveal());
        $user = $this->userService->findOrCreateForVendor($username, $vendorId);

        $this->assertInstanceOf(BenchUser::class, $user);
        $this->user->setRoles([ BenchUser::ROLE_USER ])->shouldHaveBeenCalled();
    }

    public function testForVendorIfExists()
    {
        $vendorId = 1234;
        $username = 'dantleech';

        $this->userRepository->findByVendorId($vendorId)->willReturn($this->user->reveal());
        $user = $this->userService->findOrCreateForVendor($username, $vendorId);

        $this->assertSame($this->user->reveal(), $user);
    }
}
