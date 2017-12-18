<?php

namespace App\Tests\Unit\Domain\Math;

use PHPUnit\Framework\TestCase;
use OutOfBoundsException;
use App\Domain\Math\Statistics;

class StatisticsTest extends TestCase
{
    public function testExceptionNoValues()
    {
        $this->expectException(OutOfBoundsException::class);
        Statistics::histogram([]);
    }
}
