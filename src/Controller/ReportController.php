<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Store\VariantStore;
use Twig\Environment;
use App\Domain\Report\Report;

class ReportController
{
    /**
     * @var VariantStore
     */
    private $variantStore;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(VariantStore $variantStore, Environment $twig)
    {
        $this->variantStore = $variantStore;
        $this->twig = $twig;
    }

    /**
     * @Route("/report/suite/{uuid}", name="suite_suite")
     */
    public function suite(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $report = Report::aggregate($this->variantStore->forSuiteUuid($uuid));

        return new Response($this->twig->render('report/aggregate_suite.html.twig', [
            'uuid' => $uuid,
            'report' => $report,
        ]));
    }
}
