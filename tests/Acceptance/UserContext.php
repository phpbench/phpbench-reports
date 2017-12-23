<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Behat\Tester\Exception\PendingException;
use App\Domain\User\BenchUserRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Domain\User\BenchUser;
use PHPUnit\Framework\Assert;
use App\Service\UserService;

class UserContext extends RawMinkContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var BenchUser
     */
    private $user;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->userService = $kernel->getContainer()->get(UserService::class);
    }
    /**
     * @Given the user :user exists
     */
    public function theUserExists($username, $apiKey = null)
    {
        /** @var UserService $userService */
        $this->user = $this->userService->createLocalUser($username, 'test');
    }

    /**
     * @Given I am logged in as user :username
     */
    public function iAmLoggedInAsUser($username)
    {
        $this->getSession()->visit('/login');
        $page = $this->getSession()->getPage();
        $page->fillField('username', $username);
        $page->fillField('password', 'test');
        $page->pressButton('login');
        Assert::assertContains('profile', $this->getSession()->getCurrentUrl());
    }

    /**
     * @Given I am on the profile page
     */
    public function iAmOnTheProfilePage()
    {
        $this->getSession()->visit('/profile');
    }
    /**
     * @Given I only have roles :roles
     */
    public function iOnlyHaveRoles($roles)
    {
        $roles = explode(', ', $roles);
        $this->userService->setUserRoles($this->user->username(), $roles);
    }
}
