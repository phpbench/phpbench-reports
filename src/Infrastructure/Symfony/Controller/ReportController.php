<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Store\VariantStore;
use Twig\Environment;
use App\Domain\Report\Tabulator\VariantTabulator;
use App\Domain\Store\SuiteStore;
use App\Domain\Report\Tabulator\SuiteTabulator;
use App\Domain\Report\Tabulator\IterationTabulator;
use App\Domain\Store\IterationStore;
use App\Domain\User\BenchUserRepository;
use App\Domain\Report\Tabulator\UserTabulator;
use App\Domain\Report\SuiteReport;

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

    /**
     * @var BenchUserRepository
     */
    private $userRepository;

    /**
     * @var SuiteReport
     */
    private $suiteReport;

    public function __construct(
        VariantStore $variantStore,
        Environment $twig,
        SuiteStore $suiteStore,
        IterationStore $iterationStore,
        BenchUserRepository $userRepository,
        SuiteReport $suiteReport
    )
    {
        $this->variantStore = $variantStore;
        $this->twig = $twig;
        $this->suiteStore = $suiteStore;
        $this->iterationStore = $iterationStore;
        $this->userRepository = $userRepository;
        $this->suiteReport = $suiteReport;
    }

    /**
     * @Route("/", name="home")
     */
    public function allSuites(Request $request)
    {
        $suitesReport = $this->suiteReport->allSuites();

        return new Response($this->twig->render('report/report_all_suites.html.twig', [
            'suitesReport' => $suitesReport,
        ]));
    }

    /**
     * @Route("/user/{username}", name="report_user")
     */
    public function user(Request $request)
    {
        $suitesReport = $this->suiteReport->suitesForUser($request->attributes->get('username'));

        return new Response($this->twig->render('report/report_user.html.twig', [
            'username' => $username,
            'suitesReport' => $suitesReport,
        ]));
    }

    /**
     * @Route("/report/suite/{uuid}", name="report_suite")
     */
    public function suite(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $suiteReport = SuiteTabulator::env($this->suiteStore->forSuiteUuid($uuid));
        $variantTables = VariantTabulator::aggregate($this->variantStore->forSuiteUuid($uuid));
        $suiteChart = VariantTabulator::chart(
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
        $variantTables = VariantTabulator::aggregate(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );
        $variantChart = VariantTabulator::chart(
            $this->variantStore->forSuiteUuidAndBenchmark($uuid, $class)
        );

        return new Response($this->twig->render('report/report_benchmark.html.twig', [
            'uuid' => $uuid,
            'class' => $class,
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

        $iterationTable = IterationTabulator::iterations(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
        $iterationChart = IterationTabulator::chart(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );
        $histogramChart = IterationTabulator::histogram(
            $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant($uuid, $class, $subject, $variant)
        );

        return new Response($this->twig->render('report/report_variant.html.twig', [
            'uuid' => $uuid,
            'class' => $class,
            'subject' => $subject,
            'variant' => $variant,
            'iterationTable' => $iterationTable,
            'iterationChart' => $iterationChart,
            'histogramChart' => $histogramChart,
        ]));
    }
}
