<?php

namespace App\Infrastructure\Symfony\Auth;

use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\User\BenchUserRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class ApiKeyAuthenticator implements AuthenticatorInterface
{
    const HEADER_API_KEY = 'X-API-Key';

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    public function __construct(
        BenchUserRepository $userRepository
    )
    {
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
        $data = array(
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request)
    {
        return $request->headers->has(self::HEADER_API_KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials(Request $request)
    {
        return $request->headers->get(self::HEADER_API_KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this->userRepository->findByApiKey($credentials);
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
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
