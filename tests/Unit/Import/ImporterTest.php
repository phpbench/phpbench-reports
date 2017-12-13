<?php

namespace App\Tests\Unit\Import;

use PHPUnit\Framework\TestCase;
use App\Domain\Store\VariantStore;
use App\Domain\Import\Importer;
use PhpBench\Dom\Document;
use Prophecy\Argument;

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

    public function setUp()
    {
        $this->variantStore = $this->prophesize(VariantStore::class);
        $this->importer = new Importer($this->variantStore->reveal());
    }

    public function testImport()
    {
        $document = new Document();
        $document->loadXML(file_get_contents(__DIR__ . '/../../Fixtures/suite1.xml'));

        $this->variantStore->store(
            Argument::any(),
            [
                [
                    'suite-uuid' => '1234',
                    'env-uname-os' => 'Linux',
                    'env-uname-host' => 'dtlx1',
                    'env-php-version' => '7.1',
                    'benchmark-class' => 'HashingBench',
                    'benchmark-subject-name' => 'benchMd5',
                    'benchmark-subject-variant-sleep' => 10,
                    'benchmark-subject-variant-stats-max' => '0.953',
                ]
            ]
        )->shouldBeCalled();

        $this->importer->import($document);
    }
}
