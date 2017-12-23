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

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    /**
     * @Given the user :user exists
     */
    public function theUserExists($username, $apiKey = null)
    {
        /** @var UserService $userService */
        $userService = $this->kernel->getContainer()->get(UserService::class);
        $user = $userService->createLocalUser($username, 'test');
        $this->user = $user;
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
     * @Given I only have roles :arg1
     */
    public function iOnlyHaveRoles($arg1)
    {
        throw new PendingException();
    }
}
