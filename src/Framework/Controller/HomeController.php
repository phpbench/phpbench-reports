<?php

namespace App\Framework\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return new RedirectResponse($this->urlGenerator->generate('latest'));
    }
}
