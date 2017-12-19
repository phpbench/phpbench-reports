<?php

namespace App\Tests\Unit\Domain\Project;

use PHPUnit\Framework\TestCase;
use App\Domain\Project\ProjectName;

class ProjectNameTest extends TestCase
{
    public function testFromComposite()
    {
        $name = ProjectName::fromComposite('foobar/barfoo');
        $this->assertEquals('barfoo', $name->name());
        $this->assertEquals('foobar', $name->namespace());
    }
}
