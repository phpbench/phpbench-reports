<?php

namespace App\Tests\Unit\Domain\Report;

use PHPUnit\Framework\TestCase;
use App\Domain\Report\DataSet;

class DataSetTest extends TestCase
{
    public function testImplementsArrayAccess()
    {
        $dataSet = DataSet::fromArray([
            'one' => 'two'
        ]);
        $this->assertEquals('two', $dataSet['one']);
    }

    public function testIsIterable()
    {
        $this->assertTrue(is_iterable(DataSet::fromArray([])));
        foreach (DataSet::fromArray([
            'one' => 'two',
        ]) as $key => $value) {
            $this->assertEquals('one', $key);
            $this->assertEquals('two', $value);
        }
    }

    public function testReturnsArraysAsDataSet()
    {
        $dataSet = DataSet::fromArray([
            'one' => [
                'two' => 'three',
            ],
        ]);

        $this->assertInstanceOf(DataSet::class, $dataSet['one']);
    }
}
