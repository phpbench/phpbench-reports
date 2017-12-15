<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Store\VariantStore;
use Twig\Environment;
use App\Domain\Report\VariantReport;
use App\Domain\Store\SuiteStore;
use App\Domain\Report\EnvReport;
use App\Domain\Report\IterationReport;
use App\Domain\Store\IterationStore;

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

    /**
     * @var SuiteStore
     */
    private $suiteStore;

    /**
     * @var IterationStore
     */
    private $iterationStore;

    public function __construct(
        VariantStore $variantStore,
        Environment $twig,
        SuiteStore $suiteStore,
        IterationStore $iterationStore
    )
    {
        $this->variantStore = $variantStore;
        $this->twig = $twig;
        $this->suiteStore = $suiteStore;
        $this->iterationStore = $iterationStore;
    }

    /**
     * @Route("/report/suite/{uuid}", name="report_suite")
     */
    public function suite(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $suiteReport = EnvReport::env($this->suiteStore->forSuiteUuid($uuid));
        $variantTables = VariantReport::aggregate($this->variantStore->forSuiteUuid($uuid));
        $suiteChart = VariantReport::chart(
            $this->variantStore->forSuiteUuid($uuid)
        );

        return new Response($this->twig->render('report/report_suite.html.twig', [
            'uuid' => $uuid,
            'suiteReport' => $suiteReport,
            'suiteChart' => $suiteChart,
            'variantTables' => $variantTables,
        ]));
    }

    /**
     * @Route("/report/suite/{uuid}/benchmark/{class}", name="report_benchmark")
     */
    public function benchmark(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $class = $request->attributes->get('class');
        $variantTables = VariantReport::aggregate(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
        $variantChart = VariantReport::chart(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );

        return new Response($this->twig->render('report/report_benchmark.html.twig', [
            'uuid' => $uuid,
            'variantTables' => $variantTables,
            'variantChart' => $variantChart,
        ]));
    }

    /**
     * @Route("/report/suite/{uuid}/benchmark/{class}/subject/{subject}/variant/{variant}", name="report_variant")
     */
    public function variant(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $class = $request->attributes->get('class');
        $subject = $request->attributes->get('subject');
        $variant = $request->attributes->get('variant');

        $iterationTable = IterationReport::iterations(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );

        return new Response($this->twig->render('report/report_variant.html.twig', [
            'uuid' => $uuid,
            'class' => $class,
            'subject' => $subject,
            'variant' => $variant,
            'iterationTable' => $iterationTable,
        ]));
    }
}
