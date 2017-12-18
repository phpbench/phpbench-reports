<?php

namespace App\Tests\Unit\Domain\Report;

use PHPUnit\Framework\TestCase;
use App\Domain\Report\VariantReport;

class VariantReportTest extends TestCase
{
    public function testAggregate()
    {
        $dataSet = [
            [
                'benchmark-class' => 'BenchOne',
                'subject-name' => 'one',
                'stats-max' => '185742.7',
                'stats-mean' => '180819.35',
            ],
            [
                'benchmark-class' => 'BenchOne',
                'subject-name' => 'two',
                'stats-max' => '185742.7',
                'stats-mean' => '180819.35',
            ],
            [
                'benchmark-class' => 'BenchTwo',
                'subject-name' => 'three',
                'stats-max' => '185742.7',
                'stats-mean' => '180819.35',
            ],
        ];

        $expected = [
            'BenchOne' => [
                0 => [
                    'benchmark-class' => 'BenchOne',
                    'subject-name' => 'one',
                    'stats-max' => '185742.7',
                    'stats-mean' => '180819.35',
                ],
                1 => [
                    'benchmark-class' => 'BenchOne',
                    'subject-name' => 'two',
                    'stats-max' => '185742.7',
                    'stats-mean' => '180819.35',
                ],
            ],
            'BenchTwo' => [
                0 => [
                    'benchmark-class' => 'BenchTwo',
                    'subject-name' => 'three',
                    'stats-max' => '185742.7',
                    'stats-mean' => '180819.35',
                ],
            ],
        ];

        $report = VariantReport::aggregate($dataSet);
        $this->assertEquals($expected, $report);
    }
}
