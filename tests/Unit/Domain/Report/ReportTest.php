<?php

namespace App\Tests\Unit\Domain\Report;

use PHPUnit\Framework\TestCase;
use App\Domain\Report\Report;

class ReportTest extends TestCase
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
                'stats-max' => '185742.7',
                'stats-mean' => '180819.35',
            ],
        ];

        $expected = [
            'benchmark-class: BenchOne' => [
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
            'benchmark-class: BenchTwo' => [
                2 => [
                    'benchmark-class' => 'BenchTwo',
                    'stats-max' => '185742.7',
                    'stats-mean' => '180819.35',
                ],
            ],
        ];

        $report = Report::aggregate($dataSet);
        $this->assertEquals($expected, $report);
    }
}
