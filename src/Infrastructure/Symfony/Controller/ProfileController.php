<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(
        Environment $twig,
        TokenStorage $tokenStorage
    ) {
        $this->twig = $twig;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $projects = $this->profileService->projects($user);

        return new Response($this->twig->render('user/profile.html.twig'));
    }
}
