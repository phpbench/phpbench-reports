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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class AuthController
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var AuthenticationUtils
     */
    private $authUtils;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        Provider $provider,
        UrlGeneratorInterface $urlGenerator,
        AuthenticationUtils $authUtils,
        Environment $twig
    )
    {
        $this->provider = $provider;
        $this->urlGenerator = $urlGenerator;
        $this->authUtils = $authUtils;
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        $error = $this->authUtils->getLastAuthenticationError();
        $lastUsername = $this->authUtils->getLastUsername();

        return new Response($this->twig->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]));
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
    }
}
