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
    public function theUserExists($username)
    {
        /** @var DoctrineUserRepository $userRepository */
        $userRepository = $this->kernel->getContainer()->get(DoctrineUserRepository::class);

        if (null === $user = $userRepository->findByUsername($username)) {
            $user = $userRepository->create($username, 1, '$2y$12$C8sHO2VzPQG0igceHzAG/eYwGmFFciJXq4VMa3BnFDUjsLnGwrYaK'); // p/w: test
        }
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
     * @Given I am on the API key page
     */
    public function iAmOnTheApiKeyPage()
    {
        $this->getSession()->visit('/login');
    }

    /**
     * @Then I should see my API key
     */
    public function iShouldSeeMyApiKey()
    {
        Assert::assertContains($this->user->apiKey(), $this->getSession()->getPage()->getContent());
    }

    /**
     * @Given I am on the profile page
     */
    public function iAmOnTheProfilePage()
    {
        $this->getSession()->visit('/profile');
    }
}
