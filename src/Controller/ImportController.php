<?php

namespace App\Controller;

use App\Domain\Import\Importer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpBench\Dom\Document;
use App\Domain\SuiteStorage;

class ImportController
{
    /**
     * @var Importer
     */
    private $importer;

    /**
     * @var SuiteStorage
     */
    private $storage;

    public function __construct(Importer $importer, SuiteStorage $storage)
    {
        $this->importer = $importer;
        $this->storage = $storage;
    }

    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request)
    {
        $payload = $request->getContent();
        $document = new Document();
        $document->loadXML($payload);
        $id = $this->importer->import($document);
        $this->storage->storePayload($id, $payload);

        return new JsonResponse(['ok']);
    }
}
