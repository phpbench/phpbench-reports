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
use App\Domain\Report\Tabulator\UserTabulator;
use App\Domain\Report\SuiteReport;
use App\Domain\Report\VariantReport;
use App\Domain\Report\IterationReport;
use App\Domain\User\UserNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Domain\Project\ProjectName;

class ReportController
{

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var SuiteReport
     */
    private $suiteReport;

    /**
     * @var VariantReport
     */
    private $variantReport;

    /**
     * @var IterationReport
     */
    private $iterationReport;

    public function __construct(
        Environment $twig,
        SuiteReport $suiteReport,
        VariantReport $variantReport,
        IterationReport $iterationReport
    )
    {
        $this->twig = $twig;
        $this->suiteReport = $suiteReport;
        $this->variantReport = $variantReport;
        $this->iterationReport = $iterationReport;
    }

    /**
     * @Route("/latest", name="latest")
     */
    public function latestSuites(Request $request)
    {
        $suitesReport = $this->suiteReport->allSuites();

        return new Response($this->twig->render('report/report_all_suites.html.twig', [
            'suitesReport' => $suitesReport,
        ]));
    }

    /**
     * @Route("/p/{namespace}", name="report_namespace")
     */
    public function namespace(Request $request)
    {
        $namespace = $request->attributes->get('namespace');
        $suitesReport = $this->suiteReport->suitesForNamespace($namespace);

        return new Response($this->twig->render('report/report_namespace.html.twig', [
            'namespace' => $namespace,
            'suitesReport' => $suitesReport,
        ]));
    }

    /**
     * @Route("/p/{namespace}/{name}", name="report_project")
     */
    public function project(Request $request)
    {
        $namespace = $request->attributes->get('namespace');
        $name = $request->attributes->get('name');

        $projectName = ProjectName::fromNamespaceAndName($namespace, $name);
        $suitesReport = $this->suiteReport->suitesForProject($projectName);

        return new Response($this->twig->render('report/report_project.html.twig', [
            'project' => $projectName,
            'suitesReport' => $suitesReport,
        ]));
    }

    /**
     * @Route("/report/suite/{uuid}", name="report_suite")
     */
    public function suite(Request $request)
    {
        $uuid = $request->attributes->get('uuid');

        return new Response($this->twig->render('report/report_suite.html.twig', [
            'uuid' => $uuid,
            'suiteReport' => $this->suiteReport->environmentFor($uuid),
            'suiteChart' => $this->variantReport->chartForUuid($uuid),
            'variantTables' => $this->variantReport->aggregatesForUuid($uuid),
        ]));
    }

    /**
     * @Route("/report/suite/{uuid}/benchmark/{class}", name="report_benchmark")
     */
    public function benchmark(Request $request)
    {
        $uuid = $request->attributes->get('uuid');
        $class = $request->attributes->get('class');

        return new Response($this->twig->render('report/report_benchmark.html.twig', [
            'uuid' => $uuid,
            'class' => $class,
            'variantTables' => $this->variantReport->aggregatesForUuidAndClass($uuid, $class),
            'variantChart' => $this->variantReport->chartForUuidAndClass($uuid, $class),
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

        return new Response($this->twig->render('report/report_variant.html.twig', [
            'uuid' => $uuid,
            'class' => $class,
            'subject' => $subject,
            'variant' => $variant,
            'iterationTable' => $this->iterationReport->iterationsForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant),
            'iterationChart' => $this->iterationReport->chartForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant),
            'histogramChart' => $this->iterationReport->histogramForUuidClassSubjectAndVariant($uuid, $class, $subject, $variant),
        ]));
    }
}
