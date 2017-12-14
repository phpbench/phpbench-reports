<?php

namespace App\Controller;

use App\Domain\Import\Importer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpBench\Dom\Document;
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
        $id = $this->importer->importFromPayload($request->getContent());

        return new JsonResponse([
            'suite_url' => $this->generator->generate('report_aggregate', [
                'uuid' => $id,
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}
