<?php

namespace App\Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ImporterService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ImportController
{
    /**
     * @var ImporterService
     */
    private $importer;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(ImporterService $importer, UrlGeneratorInterface $generator)
    {
        $this->importer = $importer;
        $this->generator = $generator;
    }

    /**
     * @Route("/import", name="import", methods={"POST"}, defaults={"_format": "json"})
     */
    public function import(Request $request)
    {
        $response = $this->importer->importFromPayload(
            $request->getContent(),
            $request->headers->get('X-API-Key')
        );

        return new JsonResponse([
            'suite_url' => $this->generator->generate('report_suite', [
                'namespace' => $response->project()->namespace(),
                'project' => $response->project()->name(),
                'uuid' => $response->uuid(),
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}
