<?php

namespace App\Tests\Unit\Domain\Import;

use PHPUnit\Framework\TestCase;
use App\Domain\Store\VariantStore;
use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use Prophecy\Argument;
use App\Domain\Store\SuiteStore;
use App\Domain\Store\IterationStore;
use App\Infrastructure\InMemory\Store\InMemorySuiteStore;
use App\Infrastructure\InMemory\Store\InMemoryVariantStore;
use App\Infrastructure\InMemory\Store\InMemoryIterationStore;

class ImporterTest extends TestCase
{
    /**
     * @var ObjectProphecy|VariantStore
     */
    private $variantStore;

    /**
     * @var Importer
     */
    private $importer;

    /**
     * @var ObjectProphecy|SuiteStore
     */
    private $suiteStore;

    public function setUp()
    {
        $this->variantStore = $this->prophesize(VariantStore::class);
        $this->iterationStore = new InMemoryIterationStore();
        $this->suiteStore = new InMemorySuiteStore();
        $this->variantStore = new InMemoryVariantStore();
        $this->importer = new Importer(
            $this->variantStore,
            $this->suiteStore,
            $this->iterationStore
        );
    }

    public function testImport()
    {
        $document = new Document();
        $document->loadXML(file_get_contents(__DIR__ . '/../../../Fixtures/suite1.xml'));

        $this->importer->import($document);

        $this->assertContains([
            'suite-uuid' => '1234',
            'env-uname-os' => 'Linux',
            'env-uname-host' => 'dtlx1',
            'env-php-version' => '7.1',
            'project' => 'foo/bar',
            'project-id' => '1234',
            'project-name' => 'dan',
            'project-namespace' => 'leech',
        ], $this->suiteStore->forSuiteUuid('1234'));

        $this->assertContains([
            'suite-uuid' => '1234',
            'env-uname-os' => 'Linux',
            'env-uname-host' => 'dtlx1',
            'env-php-version' => '7.1',
            'benchmark-class' => 'HashingBench',
            'subject-name' => 'benchMd5',
            'variant-index' => 0,
            'variant-sleep' => 10,
            'variant-iterations' => 1,
            'stats-max' => '0.953',
        ], $this->variantStore->forSuiteUuid('1234'));

        $this->assertContains([
            'suite-uuid' => '1234',
            'benchmark-class' => 'HashingBench',
            'subject-name' => 'benchMd5',
            'variant-index' => 0,
            'time-net' => 100,
            'mem-peak' => 2000,
            'comp-z-value' => -0.47,
            'iteration' => 0,
        ], $this->iterationStore->forSuiteUuidBenchmarkSubjectAndVariant(
            '1234',
            'HashingBench',
            'benchMd5',
            0
        ));
    }
}
