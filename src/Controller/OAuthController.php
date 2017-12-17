<?php

namespace App\Controller;

use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Auth\ProviderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Auth\Provider;

class OAuthController
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        Provider $provider,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->provider = $provider;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/connect", name="connect")
     */
    public function connect()
    {
        return new RedirectResponse($this->provider->authorizationUrl());
    }

    /**
     * @Route("/logout", name="disconnect")
     */
    public function disconnect(Request $request)
    {
        return new RedirectResponse('home');
    }

    /**
     * @Route("/connect/check", name="connect_check")
     */
    public function check(Request $request)
    {
        return new Response('Fuck', 200);
    }
}
