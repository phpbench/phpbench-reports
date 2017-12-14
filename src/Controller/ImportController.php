<?php

namespace App\Controller;

use App\Domain\Import\Importer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpBench\Dom\Document;
use App\Domain\SuiteStorage;
use App\Service\ImporterService;

class ImportController
{
    /**
     * @var ImporterService
     */
    private $importer;

    public function __construct(ImporterService $importer)
    {
        $this->importer = $importer;
    }

    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request)
    {
        $id = $this->importer->importFromPayload($request->getContent());

        return new JsonResponse(['ok']);
    }
}
