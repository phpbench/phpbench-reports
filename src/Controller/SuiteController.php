<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Store\VariantStore;
use Twig\Environment;
use App\Domain\Report\Report;

class SuiteController
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
     * @Route("/report/aggregate/suite/{uuid}", name="report_aggregate")
     */
    public function aggregate(Request $request)
    {
        $report = Report::aggregate($this->variantStore->forSuiteUuid($request->attributes->get('uuid')));

        return new Response($this->twig->render('report/aggregate_suite.html.twig', [
            'report' => $report,
        ]));
    }
}
