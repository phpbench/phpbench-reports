<?php

namespace App\Controller;

use App\Domain\Import\Importer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpBench\Dom\Document;

class ImportController
{
    /**
     * @var Importer
     */
    private $importer;

    public function __construct(Importer $importer)
    {
        $this->importer = $importer;
    }

    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request)
    {
        $payload = $request->getContent();
        $document = new Document();
        $document->loadXML($payload);
        $this->importer->import($document);

        return new JsonResponse(['ok']);
    }
}
