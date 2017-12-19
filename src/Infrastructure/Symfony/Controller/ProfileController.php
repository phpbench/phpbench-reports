<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        Environment $twig
    ) {
        $this->twig = $twig;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request)
    {
        return new Response($this->twig->render('user/profile.html.twig'));
    }
}
