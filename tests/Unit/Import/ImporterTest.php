<?php

namespace App\Tests\Unit\Import;

use PHPUnit\Framework\TestCase;
use App\Domain\Store\VariantStore;
use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use Prophecy\Argument;
use App\Domain\Store\SuiteStore;
use App\Domain\Store\IterationStore;

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
        $this->iterationStore = $this->prophesize(IterationStore::class);
        $this->suiteStore = $this->prophesize(SuiteStore::class);
        $this->importer = new Importer(
            $this->variantStore->reveal(),
            $this->suiteStore->reveal(),
            $this->iterationStore->reveal()
        );
    }

    public function testImport()
    {
        $document = new Document();
        $document->loadXML(file_get_contents(__DIR__ . '/../../Fixtures/suite1.xml'));

        $this->suiteStore->store(
            '1234',
            Argument::containing(
                [
                    'suite-uuid' => '1234',
                    'env-uname-os' => 'Linux',
                    'env-uname-host' => 'dtlx1',
                    'env-php-version' => '7.1',
                ]
            )
        )->shouldBeCalled();

        $this->variantStore->store(
            Argument::any(),
            Argument::containing(
                [
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
                ]
            )
        )->shouldBeCalled();

        $this->iterationStore->store(
            Argument::any(),
            Argument::containing([
                'suite-uuid' => '1234',
                'benchmark-class' => 'HashingBench',
                'subject-name' => 'benchMd5',
                'variant-index' => 0,
                'time-net' => 100,
                'mem-peak' => 2000,
                'comp-z-value' => -0.47,
            ])
        )->shouldBeCalled();
        $this->importer->import($document);
    }
}
