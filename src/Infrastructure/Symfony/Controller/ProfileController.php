<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use App\Service\ProjectService;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(
        Environment $twig,
        ProjectService $projectService
    ) {
        $this->twig = $twig;
        $this->projectService = $projectService;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, UserInterface $user)
    {
        $projects = $this->projectService->projects($user->getUsername());

        return new Response($this->twig->render('user/profile.html.twig', [
            'projects' => $projects,
        ]));
    }
}
