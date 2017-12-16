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

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ProviderFactory $providerFactory
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->providerFactory = $providerFactory;
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
        return $request->query->has('code');
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
        $user = $provider->getResourceOwner($token);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new User($credentials->getNickname(), null, [
            'ROLE_USER',
        ]);
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
