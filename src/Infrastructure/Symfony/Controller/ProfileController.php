<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use App\Service\ProjectService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormFactory;
use App\Infrastructure\Symfony\Form\ProjectAddForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Infrastructure\Symfony\Form\ProjectDto;

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

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        Environment $twig,
        ProjectService $projectService,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->projectService = $projectService;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
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

    /**
     * @Route("/profile/project/add", name="profile_project_add")
     */
    public function projectAdd(Request $request, UserInterface $user)
    {
        $form = $this->formFactory->create(ProjectAddForm::class, ProjectDto::fromNamespace($user->getUsername()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->projectService->createProject(
                $user->getUsername(),
                $user->getUsername(),
                $form->get('name')->getData()
            );

            return new RedirectResponse($this->urlGenerator->generate(
                'profile_project_details',
                [
                    'namespace' => $project->name()->namespace(),
                    'name' => $project->name()->name(),
                ]
            ));
        }

        return new Response($this->twig->render('user/project/add.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/profile/project/delete/{id}", name="profile_project_delete")
     */
    public function projectDelete(Request $request, UserInterface $user)
    {
        $project = $this->projectService->deleteProject(
            $user->getUsername(),
            $request->get('id')
        );

        return new RedirectResponse($this->urlGenerator->generate(
            'profile'
        ));
    }

    /**
     * @Route("/profile/project/details/{namespace}/{name}", name="profile_project_details")
     */
    public function projectViewDetails(Request $request, UserInterface $user)
    {
        return new Response($this->twig->render('user/project/details.html.twig', [
            'project' => $this->projectService->project(
                $user->getUsername(),
                $request->attributes->get('namespace'),
                $request->attributes->get('name')
            )
        ]));
    }
}
