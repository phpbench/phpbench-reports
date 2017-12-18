<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Domain\Import\Importer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpBench\Dom\Document;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\SuiteStorage;
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
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request)
    {
        $id = $this->importer->importFromPayload($request->getContent(), $request->headers->get('X-API-Key'));

        return new JsonResponse([
            'suite_url' => $this->generator->generate('report_suite', [
                'uuid' => $id,
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}
