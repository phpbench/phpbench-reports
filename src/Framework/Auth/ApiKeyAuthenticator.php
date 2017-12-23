<?php

namespace App\Framework\Auth;

use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use App\Domain\Project\ProjectRepository;

class ApiKeyAuthenticator implements AuthenticatorInterface
{
    const HEADER_API_KEY = 'X-API-Key';

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository
    ) {
        $this->projectRepository = $projectRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ) {
        $data = [
            'message' => 'Authentication Required'
        ];

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
        $project = $this->projectRepository->findByApiKey($credentials);

        if (null === $project) {
            return null;
        }

        return $project->user();
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
