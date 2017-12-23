<?php

namespace App\Tests\Unit\Domain\Query;

use PHPUnit\Framework\TestCase;
use App\Domain\Query\PagingContext;

class PagingContextTest extends TestCase
{
    public function testItReturnsTheOffset()
    {
        $context = PagingContext::create(0, 100);
        $this->assertEquals(0, $context->offset());

        $context = PagingContext::create(1, 100);
        $this->assertEquals(100, $context->offset());
    }
}
