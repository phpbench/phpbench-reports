<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

class CliContext implements Context
{
    /**
     * @When I run the command :command
     */
    public function iRunTheCommand($command)
    {
        $response = system(sprintf('%s/../../bin/console %s',
            __DIR__,
            $command
        ), $exitCode);

        Assert::assertEquals(0, $exitCode);
    }
}
