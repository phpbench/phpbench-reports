<?php

namespace App\Controller;

use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Auth\ProviderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;

class OAuthController
{
    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;


    public function __construct(ProviderFactory $providerFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->providerFactory = $providerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/connect", name="connect")
     */
    public function connect(Request $request)
    {
        return new RedirectResponse($this->providerFactory->githubProvider()->getAuthorizationUrl());
    }

    /**
     * @Route("/connect/check", name="connect_check")
     */
    public function check(Request $request)
    {
        $provider = $this->providerFactory->githubProvider();
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->query->get('code'),
        ]);
        $user = $provider->getResourceOwner($token);

        return new Response('Welcome ' .$user->getNickname(), 200);
    }
}
