<?php

namespace App\Auth;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\Domain\User\BenchUserRepository;

class GithubGuardAuthenticator implements AuthenticatorInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        LoggerInterface $logger,
        ProviderFactory $providerFactory,
        BenchUserRepository $userRepository
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->providerFactory = $providerFactory;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    )
    {
        return new RedirectResponse($this->urlGenerator->generate('connect'));
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request)
    {
        // TODO: Check URL
        $supports = $request->query->has('code');
        return $supports;
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials(Request $request)
    {
        $provider = $this->providerFactory->githubProvider();

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->query->get('code')
        ]);

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser($token, UserProviderInterface $userProvider)
    {
        $provider = $this->providerFactory->githubProvider();
        $owner = $provider->getResourceOwner($token);
        $githubId = $owner->getId();
        $user = $this->userRepository->findByVendorId($githubId);

        if (null === $user) {
            $user = $this->userRepository->create($owner->getNickname(), $githubId);
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new PostAuthenticationGuardToken(
            $user,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * {@inheritDoc}
     */
    public function supportsRememberMe()
    {
        return true;
    }
}
